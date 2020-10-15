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
use PHPUnit\Framework\TestCase;

/**
 * Class Test for Argument class.
 *
 * @author Romain Cottard
 * @group unit
 */
class ArgumentTest extends TestCase
{
    public function testArgumentFullNameWithoutValueExists(): void
    {
        //~ Mock parameters
        $mockArguments = array('--color');
        $argument = Argument::getInstance()->parse($mockArguments);

        $this->assertTrue($argument->has('color'));
    }

    public function testArgumentShortNameWithoutValueExists(): void
    {
        //~ Mock parameters
        $mockArguments = array('-c');
        $argument = Argument::getInstance()->parse($mockArguments);

        $this->assertTrue($argument->has('c'));
    }

    public function testArgumentFullNameWithValueExists(): void
    {
        //~ Mock parameters
        $mockArguments = array('--id=15');
        $argument = Argument::getInstance()->parse($mockArguments);

        $this->assertTrue($argument->has('id'), var_export($argument->getAll(), true));
        $this->assertSame('15', $argument->get('id'), var_export($argument->getAll(), true));
    }

    public function testArgumentShortNameWithValueWithSpaceExists(): void
    {
        //~ Mock parameters
        $mockArguments = array('-i', '15');
        $argument = Argument::getInstance()->parse($mockArguments);

        $this->assertTrue($argument->has('i'));
        $this->assertSame('15', $argument->get('i'));
    }

    public function testArgumentShortNameWithValueWithoutSpaceDoesNotExist(): void
    {
        //~ Mock parameters
        $mockArguments = array('-i15');
        $argument = Argument::getInstance()->parse($mockArguments);

        $this->assertTrue($argument->has('i'));
        $this->assertTrue($argument->get('i'));
    }

    public function testMultiplesArgumentsShortNameWithoutValuesExist(): void
    {
        //~ Mock parameters
        $mockArguments = array('-abc');
        $argument = Argument::getInstance()->parse($mockArguments);

        $this->assertTrue($argument->has('a'));
        $this->assertTrue($argument->has('b'));
        $this->assertTrue($argument->has('c'));
    }

    public function testArgumentWithFullAndShortAlias(): void
    {
        //~ Mock parameters
        $mockArguments = array('--foo=bar', '-f baz');
        $argument = Argument::getInstance()->parse($mockArguments);
        $argument->add('f', 'bal');

        $this->assertEquals('bar', $argument->get('foo'));
        $this->assertEquals('bal', $argument->get('fol', 'f'));
        $this->assertTrue($argument->has('fol', 'f'));
    }
}
