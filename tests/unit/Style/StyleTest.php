<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Eureka\Component\Console\Tests\Unit\Style;

use Eureka\Component\Console\Color\Bit24RGBColor;
use Eureka\Component\Console\Color\Bit4Color;
use Eureka\Component\Console\Color\Bit4HighColor;
use Eureka\Component\Console\Color\Bit4StandardColor;
use Eureka\Component\Console\Color\Bit8StandardColor;
use Eureka\Component\Console\Style\Style;
use Eureka\Component\Console\Terminal\Terminal;
use PHPUnit\Framework\TestCase;

class StyleTest extends TestCase
{
    public function testICanApplyBoldStyleOnText(): void
    {
        //~ Given
        $string = 'Any text';
        $csi    = Terminal::CSI;

        //~ When
        $style = (new Style())->bold();

        //~ Then
        $this->assertSame("{$csi}1m{$string}{$csi}0m", $style->apply($string));
    }

    public function testICanApplyFaintStyleOnText(): void
    {
        //~ Given
        $string = 'Any text';
        $csi    = Terminal::CSI;

        //~ When
        $style = (new Style())->faint();

        //~ Then
        $this->assertSame("{$csi}2m{$string}{$csi}0m", $style->apply($string));
    }

    public function testICanApplyItalicStyleOnText(): void
    {
        //~ Given
        $string = 'Any text';
        $csi    = Terminal::CSI;

        //~ When
        $style = (new Style())->italic();

        //~ Then
        $this->assertSame("{$csi}3m{$string}{$csi}0m", $style->apply($string));
    }

    public function testICanApplyUnderlineStyleOnText(): void
    {
        //~ Given
        $string = 'Any text';
        $csi    = Terminal::CSI;

        //~ When
        $style = (new Style())->underline();

        //~ Then
        $this->assertSame("{$csi}4m{$string}{$csi}0m", $style->apply($string));
    }

    public function testICanApplySlowBlinkStyleOnText(): void
    {
        //~ Given
        $string = 'Any text';
        $csi    = Terminal::CSI;

        //~ When
        $style = (new Style())->blink();

        //~ Then
        $this->assertSame("{$csi}5m{$string}{$csi}0m", $style->apply($string));
    }

    public function testICanApplyFastBlinkStyleOnText(): void
    {
        //~ Given
        $string = 'Any text';
        $csi    = Terminal::CSI;

        //~ When
        $style = (new Style())->blink(fast: true);

        //~ Then
        $this->assertSame("{$csi}6m{$string}{$csi}0m", $style->apply($string));
    }

    public function testICanApplyInvertStyleOnText(): void
    {
        //~ Given
        $string = 'Any text';
        $csi    = Terminal::CSI;

        //~ When
        $style = (new Style())->invert();

        //~ Then
        $this->assertSame("{$csi}7m{$string}{$csi}0m", $style->apply($string));
    }

    public function testICanApplyStrikeStyleOnText(): void
    {
        //~ Given
        $string = 'Any text';
        $csi    = Terminal::CSI;

        //~ When
        $style = (new Style())->strike();

        //~ Then
        $this->assertSame("{$csi}9m{$string}{$csi}0m", $style->apply($string));
    }

    public function testICanApplyColorStyleOnText(): void
    {
        //~ Given
        $string = 'Any text';
        $csi    = Terminal::CSI;

        //~ When
        $standard4bit = (new Style())->color(Bit4StandardColor::Red);
        $high4bit     = (new Style())->color(Bit4HighColor::Red);
        $bit8color    = (new Style())->color(Bit8StandardColor::Red);
        $bit24color   = (new Style())->color(new Bit24RGBColor(255, 0, 0));

        //~ Then
        $this->assertSame("{$csi}31m{$string}{$csi}0m", $standard4bit->apply($string));
        $this->assertSame("{$csi}91m{$string}{$csi}0m", $high4bit->apply($string));
        $this->assertSame("{$csi}38;5;1m{$string}{$csi}0m", $bit8color->apply($string));
        $this->assertSame("{$csi}38;2;255;0;0m{$string}{$csi}0m", $bit24color->apply($string));
    }

    public function testICanApplyBackgroundColorStyleOnText(): void
    {
        //~ Given
        $string = 'Any text';
        $csi    = Terminal::CSI;

        //~ When
        $standard4bit = (new Style())->background(Bit4StandardColor::Red);
        $high4bit     = (new Style())->background(Bit4HighColor::Red);
        $bit8color    = (new Style())->background(Bit8StandardColor::Red);
        $bit24color   = (new Style())->background(new Bit24RGBColor(255, 0, 0));

        //~ Then
        $this->assertSame("{$csi}41m{$string}{$csi}0m", $standard4bit->apply($string));
        $this->assertSame("{$csi}101m{$string}{$csi}0m", $high4bit->apply($string));
        $this->assertSame("{$csi}48;5;1m{$string}{$csi}0m", $bit8color->apply($string));
        $this->assertSame("{$csi}48;2;255;0;0m{$string}{$csi}0m", $bit24color->apply($string));
    }
}
