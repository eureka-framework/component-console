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

    /** @var ContainerInterface|null $container Set to true to set class as an executable script */
    private ?ContainerInterface $container = null;

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
     * @param  ContainerInterface|null $container
     * @return $this
     */
    public function setContainer(ContainerInterface $container = null): self
    {
        $this->container = $container;

        return $this;
    }

    /**
     * @param  string $description
     * @return $this
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @param  bool $executable
     * @return $this
     */
    public function setExecutable(bool $executable = true): self
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
     * @return ContainerInterface|null
     * @codeCoverageIgnore
     */
    public function getContainer(): ?ContainerInterface
    {
        return $this->container;
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
