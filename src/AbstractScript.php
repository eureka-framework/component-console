<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Eureka\Component\Console;

use Eureka\Component\Console\Exception\ScriptException;
use Eureka\Component\Console\Input\Input;
use Eureka\Component\Console\Option\OptionsParser;
use Eureka\Component\Console\Option\Options;
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

    private ?Input $input = null;
    private ?Output $output = null;
    private ?Output $outputErr = null;
    private ?Options $options = null;
    private ?Options $declaredOptions = null;

    public function help(): void
    {
        //~ Application\Script is generally the base namespace, so that part will be replaced
        $script = \str_replace(['Application\\Script\\', '\\'], ['','/'], static::class);

        //~ Build & display help
        (new Help($script, $this->declaredOptions(), $this->output(), $this->options()))->display();
    }

    /**
     * Run method.
     * Must be overridden.
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
    public function before(): void {}

    /**
     * Method called after run() method.
     * Can be overridden.
     *
     * @return void
     * @codeCoverageIgnore
     */
    public function after(): void {}

    /**
     * @codeCoverageIgnore
     */
    protected function output(): Output
    {
        if ($this->output === null) {
            throw new ScriptException('Output must be defined before using it!');
        }

        return $this->output;
    }

    protected function initOptions(Options $options): void
    {
        $this->declaredOptions = $options;
        $this->options         = (new OptionsParser($options))->parse();
    }

    /**
     * @codeCoverageIgnore
     */
    protected function declaredOptions(): Options
    {
        if ($this->declaredOptions === null) {
            throw new ScriptException('Declared options must be defined before using it!');
        }
        return $this->declaredOptions;
    }

    /**
     * @codeCoverageIgnore
     */
    protected function options(): Options
    {
        if ($this->options === null) {
            throw new ScriptException('Options must be defined before using it!');
        }
        return $this->options;
    }

    /**
     * @codeCoverageIgnore
     */
    protected function outputErr(): Output
    {
        if ($this->outputErr === null) {
            throw new ScriptException('OutputErr must be defined before using it!');
        }

        return $this->outputErr;
    }

    /**
     * @codeCoverageIgnore
     */
    protected function input(): Input
    {
        if ($this->input === null) {
            throw new ScriptException('Input must be defined before using it!');
        }

        return $this->input;
    }
}
