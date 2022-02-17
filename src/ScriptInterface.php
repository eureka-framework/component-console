<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Eureka\Component\Console;

use Psr\Container\ContainerInterface;

/**
 * Interface for console script launched by Eurekon console.
 *
 * @author  Romain Cottard
 */
interface ScriptInterface
{
    /**
     * Check if script is executable.
     *
     * @return bool
     */
    public function executable(): bool;

    /**
     * Get description of the script
     *
     * @return string
     */
    public function getDescription(): string;

    /**
     * Display help
     *
     * @return void
     */
    public function help(): void;

    /**
     * @param  ContainerInterface|null $container
     * @return static
     */
    public function setContainer(ContainerInterface $container = null): self;

    /**
     * Main method for console script.
     *
     * @return void
     */
    public function run(): void;

    /**
     * Method called before run() method.
     *
     * @return void
     */
    public function before(): void;

    /**
     * Method called after run() method.
     *
     * @return void
     */
    public function after(): void;
}
