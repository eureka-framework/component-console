<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Eureka\Component\Console\Tests\Unit\Color;

use Eureka\Component\Console\Color\Bit4Color;
use Eureka\Component\Console\Color\Bit4HighColor;
use Eureka\Component\Console\Color\Bit4StandardColor;
use PHPUnit\Framework\TestCase;

class Color4BitTest extends TestCase
{
    public function testICanGetStandardColorIndex(): void
    {
        //~ Given
        $color = Bit4StandardColor::Red;

        //~ Then
        $this->assertInstanceOf(Bit4Color::class, $color);
        $this->assertSame(Bit4StandardColor::Red->value, $color->getIndex());
    }

    public function testICanGetHighColorIndex(): void
    {
        //~ Given
        $color = Bit4HighColor::Red;

        //~ Then
        $this->assertInstanceOf(Bit4Color::class, $color);
        $this->assertSame(Bit4HighColor::Red->value, $color->getIndex());
    }
}
