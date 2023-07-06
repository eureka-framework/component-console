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
use Eureka\Component\Console\Progress\ProgressPercent;
use Eureka\Component\Console\Terminal\Terminal;
use PHPUnit\Framework\TestCase;

class ProgressPercentTest extends TestCase
{
    public function testICanRenderProgressPercent(): void
    {
        //~ Given
        $options  = (new Options())->add(new Option('no-color'));

        //~ When
        $progress = new ProgressPercent($options, 5);

        //~ Then
        $bar = $progress->render("before start");
        $this->assertSame('[  0.00%] before start', $bar);

        $progress->inc();
        $bar = $progress->render('iteration #1');
        $this->assertSame('[ 20.00%] iteration #1', $bar);

        $progress->inc();
        $bar = $progress->render('iteration #2');
        $this->assertSame('[ 40.00%] iteration #2', $bar);

        $progress->inc();
        $bar = $progress->render('iteration #3');
        $this->assertSame('[ 60.00%] iteration #3', $bar);

        $progress->inc();
        $bar = $progress->render('iteration #4');
        $this->assertSame('[ 80.00%] iteration #4', $bar);

        $progress->inc();
        $bar = $progress->render('iteration #5');
        $this->assertSame('[100.00%] iteration #5', $bar);

        $progress->inc();
        $bar = $progress->render('iteration #6');
        $this->assertSame('[100.00%] iteration #6', $bar);
    }

    public function testICanRenderProgressPercentWithColor(): void
    {
        //~ Given
        $options  = new Options();

        $green = Bit4StandardColor::Green;
        $cyan  = Bit4StandardColor::Cyan;

        //~ When
        $progress = new ProgressPercent($options, 5);

        //~ Then
        $bar = $progress->render("before start", $cyan, $green);
        $csi = Terminal::CSI;
        $this->assertSame("{$csi}32m[{$csi}0m{$csi}36m  0.00%{$csi}0m{$csi}32m]{$csi}0m before start", $bar);
    }
}
