<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Eureka\Component\Console\Tests\Unit\Output;

use Eureka\Component\Console\Output\Output;
use Eureka\Component\Console\Output\NullOutput;
use PHPUnit\Framework\TestCase;

class NullOutputTest extends TestCase
{
    public function testNullInput(): void
    {
        //~ When
        $output = new NullOutput();

        //~ Then
        $this->assertInstanceOf(Output::class, $output);

        $output->write('');
        $output->writeln('');
    }
}
