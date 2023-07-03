<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Eureka\Component\Console\Tests\Style;

use Eureka\Component\Console\Color\Bit24RGBColor;
use Eureka\Component\Console\Color\Bit4Color;
use Eureka\Component\Console\Color\Bit4HighColor;
use Eureka\Component\Console\Color\Bit4StandardColor;
use Eureka\Component\Console\Color\Bit8StandardColor;
use Eureka\Component\Console\Style\CellStyle;
use Eureka\Component\Console\Style\Style;
use Eureka\Component\Console\Table\Align;
use Eureka\Component\Console\Terminal\Terminal;
use PHPUnit\Framework\TestCase;

class CellStyleTest extends TestCase
{
    public function testICanInheritFromAnotherStyle(): void
    {
        $inheritedStyle = (new CellStyle())
            ->bold()
            ->faint()
            ->italic()
            ->underline()
            ->blink()
            ->blink(fast: true)
            ->invert()
            ->strike()
            ->color(Bit4StandardColor::Red)
            ->background(Bit4StandardColor::Red)
        ;

        $style = (new CellStyle())->inheritFrom($inheritedStyle);

        $this->assertEquals($inheritedStyle, $style);
    }

    public function testICanInheritFromAnotherEmptyStyle(): void
    {
        $inheritedStyle = new CellStyle();
        $style = (new CellStyle(11, Align::Center, false))
            ->bold()
            ->faint()
            ->italic()
            ->underline()
            ->blink()
            ->blink(fast: true)
            ->invert()
            ->strike()
            ->color(Bit4StandardColor::Red)
            ->background(Bit4StandardColor::Red)
        ;

        $mergedStyle = $style->inheritFrom($inheritedStyle);

        $this->assertEquals($style, $mergedStyle);
        $this->assertSame(11, $mergedStyle->getWidth());
        $this->assertSame(Align::Center, $mergedStyle->getAlign());
        $this->assertFalse($mergedStyle->hasPaddingSpace());
    }

    public function testICanInheritFromNoStyle(): void
    {
        $inheritedStyle = null;
        $style = (new CellStyle())->bold();

        $mergedStyle = $style->inheritFrom($inheritedStyle);

        $this->assertEquals($style, $mergedStyle);
    }
}
