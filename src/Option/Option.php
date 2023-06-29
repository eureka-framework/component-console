<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Eureka\Component\Console\Option;

/**
 * Represent one option for the command.
 *
 * @author Romain Cottard
 */
class Option implements \Stringable
{
    public function __construct(
        private readonly ?string $shortName = null,
        private readonly ?string $longName = null,
        private readonly string $description = '',
        private readonly bool $mandatory = false,
        private readonly bool $hasArgument = false,
        private readonly string|int|float|bool|null $default = null,
        private string|int|float|bool|null $argument = null
    ) {
    }

    public function getShortName(): ?string
    {
        return $this->shortName;
    }

    public function getLongName(): ?string
    {
        return $this->longName;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function isMandatory(): bool
    {
        return $this->mandatory;
    }

    public function hasArgument(): bool
    {
        return $this->hasArgument;
    }

    public function getArgument(): string|int|float|bool|null
    {
        return $this->argument ?? $this->default;
    }

    public function setArgument(string|int|float|bool|null $argument): static
    {
        $this->argument = $argument;

        return $this;
    }

    public function __toString(): string
    {
        return (string) ($this->longName ?? $this->shortName);
    }
}
