<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Eureka\Component\Console\Tests\Unit;

use Eureka\Component\Console\Help;
use Eureka\Component\Console\Option\Option;
use Eureka\Component\Console\Option\Options;
use Eureka\Component\Console\Output\StreamOutput;
use PHPUnit\Framework\TestCase;

class HelpTest extends TestCase
{
    /**
     * @return resource
     */
    private function getStream()
    {
        $stream = fopen('php://memory', 'r+');
        if (!is_resource($stream)) {
            $this->markTestSkipped('Cannot test method because cannot open memory stream resource');
        }

        return $stream;
    }

    public function testHelp(): void
    {
        //~ Given
        $stream  = $this->getStream();
        $declaredOptions = (new Options())
            ->add(new Option('no-color', default: false))
            ->add(new Option('arg', 'a', 'An argument', true, true))
        ;
        $parsedOptions   = (new Options())->add(new Option('no-color', default: true));

        //~ When
        $help = new Help('test', $declaredOptions, new StreamOutput($stream, false), $parsedOptions);
        $help->display();

        //~ Then
        fseek($stream, 0);
        $content = '';
        while (true) {
            $line = fgets($stream);
            if ($line === false) {
                break;
            }
            $content .= $line;
        }

        $expected = <<<HELP

        [1mUse    : [0m[1mbin/console test [OPTION]...[0m
        [1mOPTIONS:[0m
        [1m  -no-color,                            [0m
        [1m  -arg ARG,--a=ARG                      [0mAn argument - MANDATORY


        HELP;

        $this->assertSame($expected, $content);
    }
}
