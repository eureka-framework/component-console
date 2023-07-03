<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Eureka\Component\Console\Tests\Color;

use Eureka\Component\Console\Color\Bit24Color;
use Eureka\Component\Console\Color\Bit24RGBColor;
use PHPUnit\Framework\TestCase;

class Color24BitTest extends TestCase
{
    /**
     * @param int[] $expected
     * @dataProvider correctRGBProvider
     */
    public function testICanGetRGBColor(int $r, int $g, int $b, array $expected): void
    {
        //~ When
        $color = new Bit24RGBColor($r, $g, $b);

        //~ Then
        $this->assertInstanceOf(Bit24Color::class, $color);
        $this->assertSame($expected, $color->rgb());
        $this->assertSame($r, $color->r());
        $this->assertSame($g, $color->g());
        $this->assertSame($b, $color->b());
        $this->assertSame(0, $color->getIndex());
    }

    /**
     * @return array<string, array<int|int[]>>
     */
    public function correctRGBProvider(): array
    {
        return [
            'RGB "black"' => [0, 0, 0, [0, 0, 0]],
            'RGB "red"'   => [255, 0, 0, [255, 0, 0]],
            'RGB "green"' => [0, 255, 0, [0, 255, 0]],
            'RGB "blue"'  => [0, 0, 255, [0, 0, 255]],
            'RGB "white"' => [255, 255, 255, [255, 255, 255]]
        ];
    }

    /**
     * @dataProvider incorrectRGBProvider
     */
    public function testAnExceptionIsThrownWhenITryToSetAnIncorrectRGBValue(int $r, int $g, int $b): void
    {
        //~ Given
        $intensity = -1;

        //~ Then
        $this->expectException(\InvalidArgumentException::class);

        //~ When
        new Bit24RGBColor($r, $g, $b);
    }

    /**
     * @return array<string, array<int|int[]>>
     */
    public function incorrectRGBProvider(): array
    {
        return [
            'RGB below 0 for red part'   => [-1, 0, 0],
            'RGB below 0 for green part' => [0, -1, 0],
            'RGB below 0 for blue part'  => [0, 0, -1],
            'RGB above 255 for red part'   => [256, 0, 0],
            'RGB above 255 for green part' => [0, 256, 0],
            'RGB above 255 for blue part'  => [0, 0, 256],
        ];
    }
}
