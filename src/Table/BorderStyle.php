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
 * Class BorderStyle
 *
 * @author Romain Cottard
 */
class BorderStyle
{
    public const TOP      = 1;
    public const BOTTOM   = 2;
    public const VERTICAL = 3;

    public const RIGHT      = 4;
    public const LEFT       = 8;
    public const HORIZONTAL = 12;

    public const MIDDLE     = 15;

    public const DOUBLE = 32;
    public const SIMPLE = 64;
    public const MIXED  = 96;

    public const ASCII   = 128;
    public const UNICODE = 256;

    public const DOUBLE_TOP                = 1;
    public const DOUBLE_TOP_SPAN           = 2;
    public const DOUBLE_BOTTOM             = 3;
    public const DOUBLE_BOTTOM_SPAN        = 4;
    public const DOUBLE_MIDDLE             = 5;
    public const DOUBLE_MIDDLE_SPAN_TOP    = 6;
    public const DOUBLE_MIDDLE_SPAN_BOTTOM = 7;
    public const DOUBLE_MIDDLE_SPAN_BOTH   = 8;
    public const SIMPLE_TOP                = 9;
    public const SIMPLE_TOP_SPAN           = 10;
    public const SIMPLE_BOTTOM             = 11;
    public const SIMPLE_BOTTOM_SPAN        = 12;
    public const SIMPLE_MIDDLE             = 13;
    public const SIMPLE_MIDDLE_SPAN_TOP    = 14;
    public const SIMPLE_MIDDLE_SPAN_BOTTOM = 15;
    public const SIMPLE_MIDDLE_SPAN_BOTH   = 16;

    public const BORDERS = [
        self::UNICODE + self::DOUBLE + self::TOP + self::LEFT                 => '╔',
        self::UNICODE + self::DOUBLE + self::TOP + self::RIGHT                => '╗',
        self::UNICODE + self::DOUBLE + self::HORIZONTAL                       => '═',
        self::UNICODE + self::DOUBLE + self::VERTICAL                         => '║',
        self::UNICODE + self::DOUBLE + self::BOTTOM + self::LEFT              => '╚',
        self::UNICODE + self::DOUBLE + self::BOTTOM + self::RIGHT             => '╝',
        self::UNICODE + self::DOUBLE + self::HORIZONTAL + self::TOP           => '╦',
        self::UNICODE + self::DOUBLE + self::HORIZONTAL + self::BOTTOM        => '╩',
        self::UNICODE + self::DOUBLE + self::TOP + self::BOTTOM + self::LEFT  => '╠',
        self::UNICODE + self::DOUBLE + self::TOP + self::BOTTOM + self::RIGHT => '╣',
        self::UNICODE + self::DOUBLE + self::MIDDLE                           => '╬',

        self::UNICODE + self::SIMPLE + self::TOP + self::LEFT                 => '┌',
        self::UNICODE + self::SIMPLE + self::TOP + self::RIGHT                => '┐',
        self::UNICODE + self::SIMPLE + self::HORIZONTAL                       => '─',
        self::UNICODE + self::SIMPLE + self::VERTICAL                         => '│',
        self::UNICODE + self::SIMPLE + self::BOTTOM + self::LEFT              => '└',
        self::UNICODE + self::SIMPLE + self::BOTTOM + self::RIGHT             => '┘',
        self::UNICODE + self::SIMPLE + self::HORIZONTAL + self::TOP           => '┬',
        self::UNICODE + self::SIMPLE + self::HORIZONTAL + self::BOTTOM        => '┴',
        self::UNICODE + self::SIMPLE + self::VERTICAL + self::LEFT            => '├',
        self::UNICODE + self::SIMPLE + self::VERTICAL + self::RIGHT           => '┤',
        self::UNICODE + self::SIMPLE + self::MIDDLE                           => '┼',

        self::UNICODE + self::MIXED + self::TOP + self::LEFT                  => '╒',
        self::UNICODE + self::MIXED + self::TOP + self::RIGHT                 => '╕',
        self::UNICODE + self::MIXED + self::BOTTOM + self::LEFT               => '╘',
        self::UNICODE + self::MIXED + self::BOTTOM + self::RIGHT              => '╛',
        self::UNICODE + self::MIXED + self::HORIZONTAL + self::TOP            => '╤',
        self::UNICODE + self::MIXED + self::HORIZONTAL + self::BOTTOM         => '╧',
        self::UNICODE + self::MIXED + self::VERTICAL + self::LEFT             => '╟',
        self::UNICODE + self::MIXED + self::VERTICAL + self::RIGHT            => '╢',
        self::UNICODE + self::MIXED + self::MIDDLE                            => '╪',

        self::ASCII + self::DOUBLE + self::TOP + self::LEFT                   => '+',
        self::ASCII + self::DOUBLE + self::TOP + self::RIGHT                  => '+',
        self::ASCII + self::DOUBLE + self::HORIZONTAL                         => '-',
        self::ASCII + self::DOUBLE + self::VERTICAL                           => '|',
        self::ASCII + self::DOUBLE + self::BOTTOM + self::LEFT                => '+',
        self::ASCII + self::DOUBLE + self::BOTTOM + self::RIGHT               => '+',
        self::ASCII + self::DOUBLE + self::HORIZONTAL + self::TOP             => '+',
        self::ASCII + self::DOUBLE + self::HORIZONTAL + self::BOTTOM          => '+',
        self::ASCII + self::DOUBLE + self::TOP + self::BOTTOM + self::LEFT    => '+',
        self::ASCII + self::DOUBLE + self::TOP + self::BOTTOM + self::RIGHT   => '+',
        self::ASCII + self::DOUBLE + self::MIDDLE                             => '+',

        self::ASCII + self::SIMPLE + self::TOP + self::LEFT                   => '+',
        self::ASCII + self::SIMPLE + self::TOP + self::RIGHT                  => '+',
        self::ASCII + self::SIMPLE + self::HORIZONTAL                         => '-',
        self::ASCII + self::SIMPLE + self::VERTICAL                           => '|',
        self::ASCII + self::SIMPLE + self::BOTTOM + self::LEFT                => '+',
        self::ASCII + self::SIMPLE + self::BOTTOM + self::RIGHT               => '+',
        self::ASCII + self::SIMPLE + self::HORIZONTAL + self::TOP             => '+',
        self::ASCII + self::SIMPLE + self::HORIZONTAL + self::BOTTOM          => '+',
        self::ASCII + self::SIMPLE + self::VERTICAL + self::LEFT              => '+',
        self::ASCII + self::SIMPLE + self::VERTICAL + self::RIGHT             => '+',
        self::ASCII + self::SIMPLE + self::MIDDLE                             => '+',

        self::ASCII + self::MIXED + self::HORIZONTAL + self::TOP              => '+',
        self::ASCII + self::MIXED + self::HORIZONTAL + self::BOTTOM           => '+',
        self::ASCII + self::MIXED + self::VERTICAL + self::LEFT               => '+',
        self::ASCII + self::MIXED + self::VERTICAL + self::RIGHT              => '+',
        self::ASCII + self::MIXED + self::MIDDLE                              => '+',
    ];

    private int $rendering;

    public function __construct(
        int $rendering = self::UNICODE
    ) {
        $this->rendering = $rendering;
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

    public function getMiddle(int $thickness = self::SIMPLE): string
    {
        return self::BORDERS[$this->rendering + $thickness + self::MIDDLE];
    }

    /**
     * @return array<string>
     */
    public function getChars(int $barType, bool $isBar): array
    {
        $glue  = $this->getVertical(BorderStyle::SIMPLE);
        $left  = $this->getVertical(BorderStyle::DOUBLE);
        $right = $this->getVertical(BorderStyle::DOUBLE);

        if (!$isBar) {
            return [$glue, $left, $right];
        }

        switch ($barType) {
            case BorderStyle::DOUBLE_TOP:
                $glue  = $this->getHorizontalTop(BorderStyle::MIXED);
                $left  = $this->getTopLeft(BorderStyle::DOUBLE);
                $right = $this->getTopRight(BorderStyle::DOUBLE);
                break;
            case BorderStyle::DOUBLE_TOP_SPAN:
                $glue  = $this->getHorizontal(BorderStyle::DOUBLE);
                $left  = $this->getTopLeft(BorderStyle::DOUBLE);
                $right = $this->getTopRight(BorderStyle::DOUBLE);
                break;
            case BorderStyle::DOUBLE_BOTTOM:
                $glue  = $this->getHorizontalBottom(BorderStyle::MIXED);
                $left  = $this->getBottomLeft(BorderStyle::DOUBLE);
                $right = $this->getBottomRight(BorderStyle::DOUBLE);
                break;
            case BorderStyle::DOUBLE_BOTTOM_SPAN:
                $glue  = $this->getHorizontal(BorderStyle::DOUBLE);
                $left  = $this->getBottomLeft(BorderStyle::DOUBLE);
                $right = $this->getBottomRight(BorderStyle::DOUBLE);
                break;
            case BorderStyle::DOUBLE_MIDDLE:
                $glue  = $this->getMiddle(BorderStyle::MIXED);
                $left  = $this->getVerticalLeft(BorderStyle::DOUBLE);
                $right = $this->getVerticalRight(BorderStyle::DOUBLE);
                break;
            case BorderStyle::DOUBLE_MIDDLE_SPAN_TOP:
                $glue  = $this->getHorizontalTop(BorderStyle::MIXED);
                $left  = $this->getVerticalLeft(BorderStyle::DOUBLE);
                $right = $this->getVerticalRight(BorderStyle::DOUBLE);
                break;
            case BorderStyle::DOUBLE_MIDDLE_SPAN_BOTTOM:
                $glue  = $this->getHorizontalBottom(BorderStyle::MIXED);
                $left  = $this->getVerticalLeft(BorderStyle::DOUBLE);
                $right = $this->getVerticalRight(BorderStyle::DOUBLE);
                break;
            case BorderStyle::DOUBLE_MIDDLE_SPAN_BOTH:
                $glue  = $this->getHorizontal(BorderStyle::DOUBLE);
                $left  = $this->getVerticalLeft(BorderStyle::DOUBLE);
                $right = $this->getVerticalRight(BorderStyle::DOUBLE);
                break;
            case BorderStyle::SIMPLE_TOP:
                $glue  = $this->getHorizontalTop(BorderStyle::SIMPLE);
                $left  = $this->getTopLeft(BorderStyle::MIXED);
                $right = $this->getTopRight(BorderStyle::MIXED);
                break;
            case BorderStyle::SIMPLE_TOP_SPAN:
                $glue  = $this->getHorizontal(BorderStyle::SIMPLE);
                $left  = $this->getTopLeft(BorderStyle::MIXED);
                $right = $this->getTopRight(BorderStyle::MIXED);
                break;
            case BorderStyle::SIMPLE_BOTTOM:
                $glue  = $this->getHorizontalBottom(BorderStyle::SIMPLE);
                $left  = $this->getBottomLeft(BorderStyle::MIXED);
                $right = $this->getBottomRight(BorderStyle::MIXED);
                break;
            case BorderStyle::SIMPLE_BOTTOM_SPAN:
                $glue  = $this->getHorizontal(BorderStyle::SIMPLE);
                $left  = $this->getBottomLeft(BorderStyle::MIXED);
                $right = $this->getBottomRight(BorderStyle::MIXED);
                break;
            case BorderStyle::SIMPLE_MIDDLE_SPAN_TOP:
                $glue  = $this->getHorizontalTop(BorderStyle::SIMPLE);
                $left  = $this->getVerticalLeft(BorderStyle::MIXED);
                $right = $this->getVerticalRight(BorderStyle::MIXED);
                break;
            case BorderStyle::SIMPLE_MIDDLE_SPAN_BOTTOM:
                $glue  = $this->getHorizontalBottom(BorderStyle::SIMPLE);
                $left  = $this->getVerticalLeft(BorderStyle::MIXED);
                $right = $this->getVerticalRight(BorderStyle::MIXED);
                break;
            case BorderStyle::SIMPLE_MIDDLE_SPAN_BOTH:
                $glue  = $this->getHorizontal(BorderStyle::SIMPLE);
                $left  = $this->getVerticalLeft(BorderStyle::MIXED);
                $right = $this->getVerticalRight(BorderStyle::MIXED);
                break;
            case BorderStyle::SIMPLE_MIDDLE:
            default:
                $glue  = $this->getMiddle(BorderStyle::SIMPLE);
                $left  = $this->getVerticalLeft(BorderStyle::MIXED);
                $right = $this->getVerticalRight(BorderStyle::MIXED);
        }

        return [$glue, $left, $right];
    }
}
