<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Eureka\Component\Console\Tests\Unit;

use Eureka\Component\Console\AbstractScript;
use Eureka\Component\Console\Help;
use Eureka\Component\Console\Option\Options;

class Script extends AbstractScript
{
    public function __construct()
    {
        $this->setExecutable();
        $this->setDescription('Mock script for the tests');

        $this->initOptions(new Options());
    }

    public function help(): void
    {
        (new Help('script', $this->declaredOptions(), $this->output(), $this->options()))
            ->display()
        ;
    }

    public function run(): void
    {
        $this->output()->writeln('Mock Script');
    }
}
