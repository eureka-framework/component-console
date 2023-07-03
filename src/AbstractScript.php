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
use Eureka\Component\Console\Output\Output;

/**
 * Console Abstraction class.
 * Must be parent class for every console script class.
 *
 * @author  Romain Cottard
 */
abstract class AbstractScript implements ScriptInterface
{
    /** @var bool $executable Set to true to set class as an executable script */
    private bool $executable = false;

    /** @var string $description Console script description. */
    private string $description = 'Script description for Help !';

    protected Input|null $input = null;
    protected Output|null $output = null;
    protected Output|null $outputErr = null;

    /**
     * Help method.
     * Must be overridden.
     *
     * @return void
     */
    abstract public function help(): void;

    /**
     * Run method.
     * Must be overridden.
     *
     * @return void
     */
    abstract public function run(): void;

    /**
     * Set stream input & outputs
     *
     * @param Input $input
     * @param Output $output
     * @param Output $outputErr
     * @return void
     */
    public function setStreams(Input $input, Output $output, Output $outputErr): void
    {
        $this->input     = $input;
        $this->output    = $output;
        $this->outputErr = $outputErr;
    }

    /**
     * @param  string $description
     * @return $this
     */
    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @param  bool $executable
     * @return $this
     */
    public function setExecutable(bool $executable = true): static
    {
        $this->executable = $executable;

        return $this;
    }

    /**
     * Return executable status about class.
     *
     * @return bool
     */
    public function executable(): bool
    {
        return $this->executable;
    }

    /**
     * Return console script description.
     *
     * @return string
     * @codeCoverageIgnore
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Method called before run() method.
     * Can be overridden.
     *
     * @return void
     * @codeCoverageIgnore
     */
    public function before(): void
    {
    }

    /**
     * Method called after run() method.
     * Can be overridden.
     *
     * @return void
     * @codeCoverageIgnore
     */
    public function after(): void
    {
    }
}
