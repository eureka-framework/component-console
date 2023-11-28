<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Eureka\Component\Console\Tests\Unit\Table;

use Eureka\Component\Console\Table\Border;
use PHPUnit\Framework\TestCase;

class BorderTest extends TestCase
{
    public function testICanGetCharForBorder(): void
    {
        $chars = (new Border(Border::BASE))->getChars();

        $this->assertEquals(['|', '|', '|'], $chars);
    }

    public function testICanGetCharForBorderWithExtendedAscii(): void
    {
        $char = (new Border())->getCharLine(Border::DOUBLE);

        $this->assertEquals('═', $char);
    }

    public function testICanGetCharsLine(): void
    {
        $chars = (new Border())->getChars();

        $this->assertEquals(['│', '║', '║'], $chars);
    }

    /**
     * @param int $thickness
     * @param int $type
     * @param array<string> $expectedChars
     *
     * @dataProvider borderProvider
     */
    public function testICanGetCharsForInnerBar(int $thickness, int $type, array $expectedChars): void
    {
        $chars = (new Border(Border::BASE))->getCharsInnerBar($thickness, $type);

        $this->assertEquals($expectedChars, $chars);
    }

    /**
     * @return array<string,array<int|bool|array<string>>>
     */
    public static function borderProvider(): array
    {
        return [
            'default row'                  => [
                Border::DOUBLE,
                0,
                ['+', '+', '+'],
            ],
            'bar type double top'         => [
                Border::DOUBLE,
                Border::TYPE_TOP,
                ['+', '+', '+'],
            ],
            'bar type double top span'    => [
                Border::DOUBLE,
                Border::TYPE_TOP_SPAN,
                ['-', '+', '+'],
            ],
            'bar type double bottom'      => [
                Border::DOUBLE,
                Border::TYPE_BOTTOM,
                ['+', '+', '+'],
            ],
            'bar type double bottom span' => [
                Border::DOUBLE,
                Border::TYPE_BOTTOM_SPAN,
                ['-', '+', '+'],
            ],
            'bar type double INNER' => [
                Border::DOUBLE,
                Border::TYPE_INNER,
                ['+', '+', '+'],
            ],
            'bar type double INNER span top' => [
                Border::DOUBLE,
                Border::TYPE_INNER_SPAN_TOP,
                ['+', '+', '+'],
            ],
            'bar type double INNER span bottom' => [
                Border::DOUBLE,
                Border::TYPE_INNER_SPAN_BOTTOM,
                ['+', '+', '+'],
            ],
            'bar type double INNER span both' => [
                Border::DOUBLE,
                Border::TYPE_INNER_SPAN_BOTH,
                ['-', '+', '+'],
            ],
            'bar type simple top'         => [
                Border::SIMPLE,
                Border::TYPE_TOP,
                ['+', '+', '+'],
            ],
            'bar type simple top span'    => [
                Border::SIMPLE,
                Border::TYPE_TOP_SPAN,
                ['-', '+', '+'],
            ],
            'bar type simple bottom'      => [
                Border::SIMPLE,
                Border::TYPE_BOTTOM,
                ['+', '+', '+'],
            ],
            'bar type simple bottom span' => [
                Border::SIMPLE,
                Border::TYPE_BOTTOM_SPAN,
                ['-', '+', '+'],
            ],
            'bar type simple INNER' => [
                Border::SIMPLE,
                Border::TYPE_INNER,
                ['+', '+', '+'],
            ],
            'bar type simple INNER span top' => [
                Border::SIMPLE,
                Border::TYPE_INNER_SPAN_TOP,
                ['+', '+', '+'],
            ],
            'bar type simple INNER span bottom' => [
                Border::SIMPLE,
                Border::TYPE_INNER_SPAN_BOTTOM,
                ['+', '+', '+'],
            ],
            'bar type simple INNER span both' => [
                Border::SIMPLE,
                Border::TYPE_INNER_SPAN_BOTH,
                ['-', '+', '+'],
            ],
        ];
    }

    /**
     * @param int $thickness
     * @param int $type
     * @param array<string> $expectedChars
     *
     * @dataProvider borderExpendedAsciiProvider
     */
    public function testICanGetCharsForInnerBarWithExtendedAscii(int $thickness, int $type, array $expectedChars): void
    {
        $chars = (new Border())->getCharsInnerBar($thickness, $type);

        $this->assertEquals($expectedChars, $chars);
    }

    /**
     * @return array<string,array<int|bool|array<string>>>
     */
    public static function borderExpendedAsciiProvider(): array
    {
        return [
            'default row'                  => [
                Border::DOUBLE,
                0,
                ['┼', '╠', '╣'],
            ],
            'bar type double top'         => [
                Border::DOUBLE,
                Border::TYPE_TOP,
                ['╤', '╔', '╗'],
            ],
            'bar type double top span'    => [
                Border::DOUBLE,
                Border::TYPE_TOP_SPAN,
                ['═', '╔', '╗'],
            ],
            'bar type double bottom'      => [
                Border::DOUBLE,
                Border::TYPE_BOTTOM,
                ['╧', '╚', '╝'],
            ],
            'bar type double bottom span' => [
                Border::DOUBLE,
                Border::TYPE_BOTTOM_SPAN,
                ['═', '╚', '╝'],
            ],
            'bar type double INNER' => [
                Border::DOUBLE,
                Border::TYPE_INNER,
                ['╪', '╠', '╣'],
            ],
            'bar type double INNER span top' => [
                Border::DOUBLE,
                Border::TYPE_INNER_SPAN_TOP,
                ['╤', '╠', '╣'],
            ],
            'bar type double INNER span bottom' => [
                Border::DOUBLE,
                Border::TYPE_INNER_SPAN_BOTTOM,
                ['╧', '╠', '╣'],
            ],
            'bar type double INNER span both' => [
                Border::DOUBLE,
                Border::TYPE_INNER_SPAN_BOTH,
                ['═', '╠', '╣'],
            ],
            'bar type simple top'         => [
                Border::SIMPLE,
                Border::TYPE_TOP,
                ['┬', '╒', '╕'],
            ],
            'bar type simple top span'    => [
                Border::SIMPLE,
                Border::TYPE_TOP_SPAN,
                ['─', '╒', '╕'],
            ],
            'bar type simple bottom'      => [
                Border::SIMPLE,
                Border::TYPE_BOTTOM,
                ['┴', '╘', '╛'],
            ],
            'bar type simple bottom span' => [
                Border::SIMPLE,
                Border::TYPE_BOTTOM_SPAN,
                ['─', '╘', '╛'],
            ],
            'bar type simple INNER' => [
                Border::SIMPLE,
                Border::TYPE_INNER,
                ['┼', '╟', '╢'],
            ],
            'bar type simple INNER span top' => [
                Border::SIMPLE,
                Border::TYPE_INNER_SPAN_TOP,
                ['┬', '╟', '╢'],
            ],
            'bar type simple INNER span bottom' => [
                Border::SIMPLE,
                Border::TYPE_INNER_SPAN_BOTTOM,
                ['┴', '╟', '╢'],
            ],
            'bar type simple INNER span both' => [
                Border::SIMPLE,
                Border::TYPE_INNER_SPAN_BOTH,
                ['─', '╟', '╢'],
            ],
        ];
    }
}
