<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Eureka\Component\Console\Tests;

use Eureka\Component\Console\Argument\Argument;
use Eureka\Component\Console\IO\Out;
use Eureka\Component\Console\Progress\Progress;
use PHPUnit\Framework\TestCase;

/**
 * Class Test for Progress class.
 *
 * @author Romain Cottard
 * @group unit
 */
class ProgressTest extends TestCase
{
    const NB_ELEMENTS = 10;

    public function testProgressTypeBarWithProgressArgument()
    {
        //~ Mock parameters
        $mockArguments = array('--progress');
        Argument::getInstance()->parse($mockArguments);
        Out::allowBuffering(true);

        $progress = new Progress('phpunit', self::NB_ELEMENTS);
        $progress->setTypeDisplay(Progress::TYPE_BAR);

        $assert = [
            '[#####                                             ] test',
            '[##########                                        ] test',
            '[###############                                   ] test',
            '[####################                              ] test',
            '[#########################                         ] test',
            '[##############################                    ] test',
            '[###################################               ] test',
            '[########################################          ] test',
            '[#############################################     ] test',
            '[##################################################] test',
        ];

        for ($i = 0; $i < self::NB_ELEMENTS; $i++) {
            ob_start();
            $progress->display('test');
            $this->assertEquals($assert[$i], trim(ob_get_clean()));
        }

        ob_start();
        $progress->displayComplete('done');
        $this->assertEquals('[##################################################] done', trim(ob_get_clean()));
    }

    public function testProgressTypePercentWithProgressArgument()
    {
        //~ Mock parameters
        $mockArguments = array('--progress');
        Argument::getInstance()->parse($mockArguments);
        Out::allowBuffering(true);

        $progress = new Progress('phpunit', self::NB_ELEMENTS);
        $progress->setTypeDisplay(Progress::TYPE_PERCENT);

        $assert = [
            '[ 10.00%] test',
            '[ 20.00%] test',
            '[ 30.00%] test',
            '[ 40.00%] test',
            '[ 50.00%] test',
            '[ 60.00%] test',
            '[ 70.00%] test',
            '[ 80.00%] test',
            '[ 90.00%] test',
            '[100.00%] test',
        ];

        for ($i = 0; $i < self::NB_ELEMENTS; $i++) {
            ob_start();
            $progress->display('test');
            $this->assertEquals($assert[$i], trim(ob_get_clean()));
        }
    }

    public function testProgressTypeBarWithoutProgressArgument()
    {
        //~ Mock parameters
        $mockArguments = array();
        Argument::getInstance()->parse($mockArguments);
        Out::allowBuffering(true);

        $progress = new Progress('phpunit', self::NB_ELEMENTS);
        $progress->setTypeDisplay(Progress::TYPE_BAR);

        for ($i = 0; $i < self::NB_ELEMENTS; $i++) {
            ob_start();
            $progress->display('test');
            $this->assertEquals('', trim(ob_get_clean()));
        }
    }

    public function testProgressTypePercentWithoutProgressArgument()
    {
        //~ Mock parameters
        $mockArguments = array();
        Argument::getInstance()->parse($mockArguments);
        Out::allowBuffering(true);

        $progress = new Progress('phpunit', self::NB_ELEMENTS);
        $progress->setTypeDisplay(Progress::TYPE_PERCENT);

        for ($i = 0; $i < self::NB_ELEMENTS; $i++) {
            ob_start();
            $progress->display('test');
            $this->assertEquals('', trim(ob_get_clean()));
        }
    }

    public function testAnExceptionIsThrownWithITryToSetAnInvalidDisplayType()
    {
        $progress = new Progress('phpunit', self::NB_ELEMENTS);

        $this->expectException(\DomainException::class);
        $progress->setTypeDisplay(99);
    }
}
