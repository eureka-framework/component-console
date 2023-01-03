<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Eureka\Component\Console\Argument;

/**
 * Class to access and use arguments from command line in PHP CLI scripts.
 *
 * @author Romain Cottard
 */
class Argument
{
    /** @var array<string|int|float|bool> List of arguments parsed */
    protected array $arguments = [];

    /** @var Argument|null $instance Current class instance. */
    protected static ?self $instance = null;

    /**
     * Class constructor.
     */
    protected function __construct()
    {
    }

    /**
     * Get class instance (singleton pattern).
     *
     * @return Argument Class instance
     */
    public static function getInstance(): self
    {
        if (null === static::$instance) {
            static::$instance = new self();
        }

        return static::$instance;
    }

    /**
     * Add specified argument value.
     *
     * @param  string $argument Argument name
     * @param  string|int|float|bool $value Value
     * @return $this
     */
    public function add(string $argument, $value): self
    {
        $this->arguments[$argument] = $value;

        return $this;
    }

    /**
     * Get specified argument value.
     *
     * @param  string $argument Argument name
     * @param  string|null $alias Argument alias name (if exists)
     * @param  string|int|float|bool|null $default Default value if argument does not exists.
     * @return string|int|float|bool|null
     */
    public function get(string $argument, string $alias = null, $default = null)
    {
        if (isset($this->arguments[$argument])) {
            return $this->arguments[$argument];
        } else {
            if (!empty($alias) && isset($this->arguments[$alias])) {
                return $this->arguments[$alias];
            } else {
                return $default;
            }
        }
    }

    /**
     * Get all arguments
     *
     * @return array<string|int|float|bool>
     */
    public function getAll(): array
    {
        return $this->arguments;
    }

    /**
     * Check if argument exists.
     *
     * @param string $argument Argument name
     * @param string|null $alias Argument alias name (if exists)
     * @return bool
     */
    public function has(string $argument, string $alias = null): bool
    {
        if (isset($this->arguments[$argument])) {
            return true;
        } elseif (!empty($alias) && isset($this->arguments[$alias])) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Parse argument from command lines.
     *
     * @param array<string> $arguments Parameter for this function is $argv global variable.
     * @return Argument
     */
    public function parse(array $arguments): self
    {
        $this->arguments = [];
        $arguments       = new ArgumentIterator($arguments);

        foreach ($arguments as $name) {
            $isFull  = substr($name, 0, 2) === '--';
            $isShort = !$isFull && substr($name, 0, 1) === '-';
            $name    = $this->getName($name, $isFull, $isShort);

            $arguments->next();
            $value = ($arguments->valid() ? $arguments->current() : '');
            $arguments->prev();
            if (empty($value) || substr($value, 0, 1) === '-') {
                $value = true;
            }

            if ($isFull) {
                $this->parseFull($name, $value);
            } elseif ($isShort) {
                $this->parseShort($name, $value);
            } elseif ($arguments->key() !== 0 && !isset($this->arguments['__default__'])) {
                $this->arguments['__default__'] = $name;
            }
        }

        return $this;
    }

    /**
     * @param string $name
     * @param bool $isFull
     * @param bool $isShort
     * @return string
     */
    private function getName(string $name, bool $isFull, bool $isShort): string
    {
        $subString = 0;
        if ($isShort) {
            $subString = 1;
        } elseif ($isFull) {
            $subString = 2;
        }

        return substr($name, $subString);
    }

    /**
     * @param string $name
     * @param string|bool $value
     * @return void
     */
    private function parseFull(string $name, $value): void
    {
        $arg   = [];
        $match = preg_match('`([0-9a-zA-Z_-]+)="?(.+)"?`', $name, $arg);

        if ($match > 0) {
            // ~ Case '--test=value'
            $this->arguments[$arg[1]] = $arg[2];
        } else {
            // ~ Case '--test'
            $this->arguments[$name] = $value;
        }
    }

    /**
     * @param string $name
     * @param string|bool $value
     * @return void
     */
    private function parseShort(string $name, $value): void
    {
        $len = strlen($name);

        // ~ case -t
        if (1 == $len) {
            $this->arguments[$name] = $value;
            return;
        }

        //~ case -tap (equivalent to -t -a -p)
        for ($letter = 0; $letter < $len; $letter++) {
            $this->arguments[$name[$letter]] = true;
        }
    }
}
