<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Eureka\Component\Console\Progress;

use Eureka\Component\Console\Color\Color;

interface Progress
{
    public function inc(int $step = 1): static;

    public function render(string $label = '', Color|null $progress = null, Color|null $background = null): string;
}
