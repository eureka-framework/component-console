<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Eureka\Component\Console\Tests\Progress;

use Eureka\Component\Console\Color\Bit4StandardColor;
use Eureka\Component\Console\Option\Option;
use Eureka\Component\Console\Option\Options;
use Eureka\Component\Console\Progress\ProgressBar;
use Eureka\Component\Console\Terminal\Terminal;
use PHPUnit\Framework\TestCase;

class ProgressBarTest extends TestCase
{
    public function testICanRenderProgressBar(): void
    {
        //~ Given
        $options  = (new Options())->add(new Option('no-color'));

        //~ When
        $progress = new ProgressBar($options, 5, 10);

        $bar = $progress->render("before start");
        $this->assertSame('│░░░░░░░░░░│ before start', $bar);

        //~ Then
        $progress->inc();
        $bar = $progress->render('iteration #1');
        $this->assertSame('│██░░░░░░░░│ iteration #1', $bar);

        $progress->inc();
        $bar = $progress->render('iteration #2');
        $this->assertSame('│████░░░░░░│ iteration #2', $bar);

        $progress->inc();
        $bar = $progress->render('iteration #3');
        $this->assertSame('│██████░░░░│ iteration #3', $bar);

        $progress->inc();
        $bar = $progress->render('iteration #4');
        $this->assertSame('│████████░░│ iteration #4', $bar);

        $progress->inc();
        $bar = $progress->render('iteration #5');
        $this->assertSame('│██████████│ iteration #5', $bar);
    }

    public function testICanRenderProgressBarWithMaxWidthFromTerminal(): void
    {
        //~ Given
        $terminal = $this->createMock(Terminal::class);
        $terminal->method('getWidth')->willReturn(14);
        $options  = (new Options())->add(new Option('no-color'));

        //~ When
        $progress = new ProgressBar($options, 5, 0, terminal: $terminal);

        $bar = $progress->render("before start");
        $this->assertSame('│░░░░░░░░░░│ before start', $bar);

        //~ Then
        $progress->inc();
        $bar = $progress->render('iteration #1');
        $this->assertSame('│██░░░░░░░░│ iteration #1', $bar);

        $progress->inc();
        $bar = $progress->render('iteration #2');
        $this->assertSame('│████░░░░░░│ iteration #2', $bar);

        $progress->inc();
        $bar = $progress->render('iteration #3');
        $this->assertSame('│██████░░░░│ iteration #3', $bar);

        $progress->inc();
        $bar = $progress->render('iteration #4');
        $this->assertSame('│████████░░│ iteration #4', $bar);

        $progress->inc();
        $bar = $progress->render('iteration #5');
        $this->assertSame('│██████████│ iteration #5', $bar);

        $progress->inc();
        $bar = $progress->render('iteration #6');
        $this->assertSame('│██████████│ iteration #6', $bar);
    }

    public function testICanRenderProgressBarWithColor(): void
    {
        //~ Given
        $options  = new Options();

        $green = Bit4StandardColor::Green;
        $black = Bit4StandardColor::Black;

        //~ When
        $progress = new ProgressBar($options, 5, 10);

        //~ Then
        $progress->inc();
        $bar = $progress->render('iteration #1', $green, $black);
        $csi = Terminal::CSI;
        $this->assertSame("{$csi}42m{$csi}32m██{$csi}0m{$csi}30m░░░░░░░░{$csi}0m iteration #1", $bar);
    }

    public function testICanRenderProgressBarWithColorButWithoutBackgroundChar(): void
    {
        //~ Given
        $options  = new Options();

        $green = Bit4StandardColor::Green;
        $black = Bit4StandardColor::Black;

        //~ When
        $progress = new ProgressBar($options, 5, 10, backgroundChar: ' ');

        //~ Then
        $progress->inc();
        $bar = $progress->render('iteration #1', $green, $black);
        $csi = Terminal::CSI;
        $this->assertSame("{$csi}42m{$csi}32m██{$csi}0m{$csi}40m        {$csi}0m iteration #1", $bar);
    }
}
