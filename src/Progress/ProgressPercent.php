<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Eureka\Component\Console\Progress;

use Eureka\Component\Console\Color\Bit8StandardColor;
use Eureka\Component\Console\Color\Color;
use Eureka\Component\Console\Option\Options;
use Eureka\Component\Console\Style\Style;

class ProgressPercent implements Progress
{
    private int $step = 0;

    public function __construct(
        private readonly Options $options,
        private readonly int $nbElements,
        private readonly bool $capped = true,
    ) {}

    public function inc(int $step = 1): static
    {
        $this->step += $step;

        return $this;
    }

    public function render(string $label = '', ?Color $progress = null, ?Color $background = null): string
    {
        $percent = 100 / $this->nbElements * $this->step;

        if ($this->capped && $percent > 100) {
            $percent = 100;
        }

        $text = str_pad(number_format($percent, 2), 6, ' ', STR_PAD_LEFT) . '%';

        if ($this->options->has('no-color')) {
            return '[' . $text . '] ' . $label;
        }

        $progress   ??= Bit8StandardColor::Cyan;
        $background ??= Bit8StandardColor::Green;

        return
            (new Style())->color($background)->apply('[')
            . (new Style())->color($progress)->apply($text)
            . (new Style())->color($background)->apply(']')
            . " $label"
        ;
    }
}
