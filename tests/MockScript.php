<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Eureka\Component\Console\Tests;

use Eureka\Component\Console\AbstractScript;
use Eureka\Component\Console\Help;
use Eureka\Component\Console\IO\Out;
use Eureka\Component\Console\ScriptInterface;

/**
 * Class MockScript
 *
 * @author Romain Cottard
 */
class MockScript extends AbstractScript implements ScriptInterface
{
    /**
     * MockScript constructor.
     */
    public function __construct()
    {
        $this->setExecutable();
        $this->setDescription('Mock Script Class');
    }

    /**
     * @return void
     */
    public function help(): void
    {
        $help = new Help(MockScript::class);
        $help->addArgument('i', 'id', 'Argument ID', true, false);
        $help->display();
    }

    /**
     * @return void
     */
    public function before(): void
    {
        Out::std('Before.');
    }

    /**
     * @return void
     */
    public function run(): void
    {
        Out::std('Hello World!');
    }

    /**
     * @return void
     */
    public function after(): void
    {
        Out::std('After.');
    }
}
