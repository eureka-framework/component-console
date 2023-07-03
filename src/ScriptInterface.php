<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Eureka\Component\Console;

use Eureka\Component\Console\Input\Input;
use Eureka\Component\Console\Input\StreamInput;
use Eureka\Component\Console\Output\Output;
use Eureka\Component\Console\Output\StreamOutput;

/**
 * Interface for console script launched by component console.
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
     * @param  bool $executable
     * @return $this
     */
    public function setExecutable(bool $executable = true): static;

    /**
     * Get description of the script
     *
     * @return string
     */
    public function getDescription(): string;

    /**
     * @param  string $description
     * @return $this
     */
    public function setDescription(string $description): static;

    /**
     * Set stream input & outputs
     *
     * @param Input $input
     * @param Output $output
     * @param Output $outputErr
     * @return void
     */
    public function setStreams(Input $input, Output $output, Output $outputErr): void;

    /**
     * Display help
     *
     * @return void
     */
    public function help(): void;

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
