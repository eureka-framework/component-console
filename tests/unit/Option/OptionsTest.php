<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Eureka\Component\Console\Tests\Unit\Option;

use Eureka\Component\Console\Exception\InvalidOptionException;
use Eureka\Component\Console\Option\Option;
use Eureka\Component\Console\Option\OptionsParser;
use Eureka\Component\Console\Option\Options;
use PHPUnit\Framework\TestCase;

class OptionsTest extends TestCase
{
    public function testOptionGetter(): void
    {
        //~ Given
        $option = new Option('f', 'foo', 'Foo option', true, true, 'bar');

        //~ When

        //~ Then
        $this->assertSame('f', $option->getShortName());
        $this->assertSame('foo', $option->getLongName());
        $this->assertSame('Foo option', $option->getDescription());
        $this->assertSame('bar', $option->getArgument());
        $this->assertSame('foo', (string) $option);
        $this->assertTrue($option->isMandatory());
        $this->assertTrue($option->hasArgument());
    }
    public function testOptionsFullNameWithoutValueExists(): void
    {
        //~ Given
        $options       = new Options();
        $mockArguments = ['--color'];

        //~ When
        $options = (new OptionsParser($options))->parse($mockArguments);

        //~ Then
        $this->assertTrue($options->has('color'));
        $this->assertTrue($options->value('color'));
    }

    public function testOptionsShortNameWithoutValueExists(): void
    {
        //~ Given
        $options       = new Options();
        $mockArguments = ['-c'];

        //~ When
        $options = (new OptionsParser($options))->parse($mockArguments);

        //~ Then
        $this->assertTrue($options->has('c'));
    }

    public function testOptionsFullNameWithValueExists(): void
    {
        //~ Given
        $options       = new Options();
        $mockArguments = ['--id=15'];

        //~ When
        $options = (new OptionsParser($options))->parse($mockArguments);

        //~ Then
        $this->assertTrue($options->has('id'), var_export($options, true));
        $this->assertSame('15', $options->get('id')->getArgument(), var_export($options, true));
    }

    public function testOptionsShortNameWithValueWithSpaceExists(): void
    {
        //~ Given
        $options       = new Options();
        $mockArguments = ['-i', '15'];

        //~ When
        $options = (new OptionsParser($options))->parse($mockArguments);

        //~ Then
        $this->assertTrue($options->has('i'));
        $this->assertSame('15', $options->get('i')->getArgument());
    }

    public function testOptionsShortNameWithValueWithoutSpaceDoesNotExist(): void
    {
        //~ Given
        $options       = new Options();
        $mockArguments = ['-i15'];

        //~ When
        $options = (new OptionsParser($options))->parse($mockArguments);

        //~ Then
        $this->assertTrue($options->has('i'));
        $this->assertTrue($options->get('i')->getArgument());
    }

    public function testMultiplesArgumentsShortNameWithoutValuesExist(): void
    {
        //~ Given
        $options       = new Options();
        $mockArguments = ['-abc'];

        //~ When
        $options = (new OptionsParser($options))->parse($mockArguments);

        //~ Then
        $this->assertTrue($options->has('a'));
        $this->assertTrue($options->has('b'));
        $this->assertTrue($options->has('c'));
    }

    public function testOptionsWithFullAndShortAlias(): void
    {
        //~ Given
        $options       = new Options();
        $mockArguments = ['--foo=bar', '-f', 'baz'];

        //~ When
        $options = (new OptionsParser($options))->parse($mockArguments);
        $options->add(new Option(shortName: 'f', default: 'bal'));

        //~ Then
        $this->assertEquals('bar', $options->get('foo')->getArgument());
        $this->assertEquals('bal', $options->get('fol', 'f')->getArgument());
        $this->assertTrue($options->has('fol', 'f'));
    }

    public function testOptionsWithDefaultScriptArgument(): void
    {
        //~ Given
        $options       = (new Options())->add(new Option('script'));
        $mockArguments = ['bin/console', 'script/name'];

        //~ When
        $options = (new OptionsParser($options))->parse($mockArguments);

        //~ Then
        $this->assertTrue($options->has('script'));
        $this->assertEquals('script/name', $options->get('script')->getArgument());
    }

    public function testOptionsMatchDeclaredOption(): void
    {
        //~ Given
        $options       = (new Options())
            ->add(new Option('foo'))
            ->add(new Option('b'))
        ;
        $mockArguments = ['--foo=bar', '-b', 'baz'];

        //~ When
        $options = (new OptionsParser($options))->parse($mockArguments);

        //~ Then
        $this->assertTrue($options->has('foo'));
        $this->assertTrue($options->has('b'));
        $this->assertEquals('bar', $options->get('foo')->getArgument());
        $this->assertEquals('baz', $options->get('b')->getArgument());
    }

    public function testAnExceptionIsThrownWhenITryToAccessOfNonExistentOption(): void
    {
        //~ Given
        $options       = (new Options());
        $mockArguments = ['--foo=bar'];

        //~ When
        $options = (new OptionsParser($options))->parse($mockArguments);

        //~ Then
        $this->assertFalse($options->has('baz'));
        $this->expectException(InvalidOptionException::class);
        $options->get('baz');
    }

    public function testCanIterateOnOptions(): void
    {
        //~ Given
        $options       = new Options();
        $mockArguments = ['--foo=bar', '-f', 'baz'];

        //~ When
        $options = (new OptionsParser($options))->parse($mockArguments);

        foreach ($options as $option) {
            $this->assertInstanceOf(Option::class, $option);
        }
    }

    public function testCanGetNumberOfOptions(): void
    {
        //~ Given
        $options       = new Options();
        $mockArguments = ['--foo=bar', '-f', 'baz'];

        //~ When
        $options = (new OptionsParser($options))->parse($mockArguments);

        $this->assertCount(2, $options, var_export($options, true));
    }
}
