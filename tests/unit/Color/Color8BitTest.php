<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Eureka\Component\Console\Tests\Unit\Color;

use Eureka\Component\Console\Color\Bit8Color;
use Eureka\Component\Console\Color\Bit8GreyscaleColor;
use Eureka\Component\Console\Color\Bit8HighColor;
use Eureka\Component\Console\Color\Bit8RGBColor;
use Eureka\Component\Console\Color\Bit8StandardColor;
use PHPUnit\Framework\TestCase;

class Color8BitTest extends TestCase
{
    public function testICanGetGreyscaleColor(): void
    {
        //~ Given
        $intensity = 1;

        //~ When
        $color = new Bit8GreyscaleColor($intensity);

        //~ Then
        $this->assertInstanceOf(Bit8Color::class, $color);
        $this->assertSame(233, $color->getIndex());
    }

    public function testAnExceptionIsThrowWhenISetAnIntensityBelow0ForGreyscaleColor(): void
    {
        //~ Given
        $intensity = -1;

        //~ Then
        $this->expectException(\InvalidArgumentException::class);

        //~ When
        new Bit8GreyscaleColor($intensity);
    }

    public function testAnExceptionIsThrowWhenISetAnIntensityAbove23ForGreyscaleColor(): void
    {
        //~ Given
        $intensity = 24;

        //~ Then
        $this->expectException(\InvalidArgumentException::class);

        //~ When
        new Bit8GreyscaleColor($intensity);
    }

    /**
     * @dataProvider correctRGBProvider
     */
    public function testICanGetRGBColor(int $r, int $g, int $b, int $expected): void
    {
        //~ Given
        $intensity = 1;

        //~ When
        $color = new Bit8RGBColor($r, $g, $b);

        //~ Then
        $this->assertInstanceOf(Bit8Color::class, $color);
        $this->assertSame($expected, $color->getIndex());
    }

    /**
     * @return array<string, int[]>
     */
    public static function correctRGBProvider(): array
    {
        return [
            'RGB "black"' => [0, 0, 0, 16],
            'RGB "red"'   => [5, 0, 0, 196],
            'RGB "green"' => [0, 5, 0, 46],
            'RGB "blue"'  => [0, 0, 5, 21],
            'RGB "white"' => [5, 5, 5, 231]
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
        new Bit8RGBColor($r, $g, $b);
    }

    /**
     * @return array<string, int[]>
     */
    public static function incorrectRGBProvider(): array
    {
        return [
            'RGB below 0 for red part'   => [-1, 0, 0],
            'RGB below 0 for green part' => [0, -1, 0],
            'RGB below 0 for blue part'  => [0, 0, -1],
            'RGB above 5 for red part'   => [6, 0, 0],
            'RGB above 5 for green part' => [0, 6, 0],
            'RGB above 5 for blue part'  => [0, 0, 6],
        ];
    }

    public function testICanGetStandardColorIndex(): void
    {
        //~ Given
        $color = Bit8StandardColor::Red;

        //~ Then
        $this->assertInstanceOf(Bit8Color::class, $color);
        $this->assertSame(Bit8StandardColor::Red->value, $color->getIndex());
    }

    public function testICanGetHighColorIndex(): void
    {
        //~ Given
        $color = Bit8HighColor::Red;

        //~ Then
        $this->assertInstanceOf(Bit8Color::class, $color);
        $this->assertSame(Bit8HighColor::Red->value, $color->getIndex());
    }
}
