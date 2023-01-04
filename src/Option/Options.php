<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Eureka\Component\Console\Option;

use Eureka\Component\Console\Exception\InvalidOptionException;

/**
 * Option collection (from parsing or declared in Script / Console)
 *
 * @author Romain Cottard
 */
class Options
{
    /** @var array<string, Option> $options List of options */
    protected array $options = [];

    /**
     * Add an option to collection.
     *
     * @param Option $option Options
     * @return self
     */
    public function add(Option $option): self
    {
        //~ Store by long name if present
        if ($option->getLongName() !== null) {
            $this->options[$option->getLongName()] = $option;
        }

        //~ Also store by short name if present
        if ($option->getShortName() !== null) {
            $this->options[$option->getShortName()] = $option;
        }

        return $this;
    }

    /**
     * Get specified argument value.
     *
     * @param string $name Option name
     * @param string|null $alias Option alias name (if exists)
     * @return Option
     */
    public function get(string $name, ?string $alias = null): Option
    {
        if (!$this->has($name, $alias)) {
            throw new InvalidOptionException("Option '$name' (alias: '$alias') does not exists!");
        }

        return $this->options[$name] ?? $this->options[$alias];
    }

    /**
     * Check if option exists.
     *
     * @param string $name Option name
     * @param string|null $alias Option alias name (if exists)
     * @return bool
     */
    public function has(string $name, string $alias = null): bool
    {
        return (isset($this->options[$name]) || (!empty($alias) && isset($this->options[$alias])));
    }
}
