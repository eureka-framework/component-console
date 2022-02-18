<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Eureka\Component\Console\Tests;

use Eureka\Component\Console\Table\BorderStyle;
use PHPUnit\Framework\TestCase;

/**
 * Class to test border styles table
 *
 * @author Romain Cottard
 * @group unit
 */
class BorderStyleTest extends TestCase
{
    /**
     * @param int $type
     * @param bool $isBar
     * @param array<string> $expectedChars
     * @return void
     *
     * @dataProvider borderStyleProvider
     */
    public function testICanGetCharForBorder(int $type, bool $isBar, array $expectedChars): void
    {
        $chars = (new BorderStyle(BorderStyle::UNICODE))->getChars($type, $isBar);

        $this->assertEquals($expectedChars, $chars);
    }

    /**
     * @return array<string,array<int|bool|array<string>>>
     */
    public function borderStyleProvider(): array
    {
        return [
            'normal row'                  => [
                0,
                false,
                ['│', '║', '║'],
            ],
            'bar type double top'         => [
                BorderStyle::DOUBLE_TOP,
                true,
                ['╤', '╔', '╗'],
            ],
            'bar type double top span'    => [
                BorderStyle::DOUBLE_TOP_SPAN,
                true,
                ['═', '╔', '╗'],
            ],
            'bar type double bottom'      => [
                BorderStyle::DOUBLE_BOTTOM,
                true,
                ['╧', '╚', '╝'],
            ],
            'bar type double bottom span' => [
                BorderStyle::DOUBLE_BOTTOM_SPAN,
                true,
                ['═', '╚', '╝'],
            ],
            'bar type double middle' => [
                BorderStyle::DOUBLE_MIDDLE,
                true,
                ['╪', '╠', '╣'],
            ],
            'bar type double middle span top' => [
                BorderStyle::DOUBLE_MIDDLE_SPAN_TOP,
                true,
                ['╤', '╠', '╣'],
            ],
            'bar type double middle span bottom' => [
                BorderStyle::DOUBLE_MIDDLE_SPAN_BOTTOM,
                true,
                ['╧', '╠', '╣'],
            ],
            'bar type double middle span both' => [
                BorderStyle::DOUBLE_MIDDLE_SPAN_BOTH,
                true,
                ['═', '╠', '╣'],
            ],
            'bar type simple top'         => [
                BorderStyle::SIMPLE_TOP,
                true,
                ['┬', '╒', '╕'],
            ],
            'bar type simple top span'    => [
                BorderStyle::SIMPLE_TOP_SPAN,
                true,
                ['─', '╒', '╕'],
            ],
            'bar type simple bottom'      => [
                BorderStyle::SIMPLE_BOTTOM,
                true,
                ['┴', '╘', '╛'],
            ],
            'bar type simple bottom span' => [
                BorderStyle::SIMPLE_BOTTOM_SPAN,
                true,
                ['─', '╘', '╛'],
            ],
            'bar type simple middle' => [
                BorderStyle::SIMPLE_MIDDLE,
                true,
                ['┼', '╟', '╢'],
            ],
            'bar type simple middle span top' => [
                BorderStyle::SIMPLE_MIDDLE_SPAN_TOP,
                true,
                ['┬', '╟', '╢'],
            ],
            'bar type simple middle span bottom' => [
                BorderStyle::SIMPLE_MIDDLE_SPAN_BOTTOM,
                true,
                ['┴', '╟', '╢'],
            ],
            'bar type simple middle span both' => [
                BorderStyle::SIMPLE_MIDDLE_SPAN_BOTH,
                true,
                ['─', '╟', '╢'],
            ],
        ];
    }
}
