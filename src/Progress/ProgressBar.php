<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Eureka\Component\Console\Progress;

use Eureka\Component\Console\Color\Bit8GreyscaleColor;
use Eureka\Component\Console\Color\Bit8RGBColor;
use Eureka\Component\Console\Color\Color;
use Eureka\Component\Console\Option\Options;
use Eureka\Component\Console\Style\Style;
use Eureka\Component\Console\Terminal\Terminal;

class ProgressBar implements Progress
{
    private int $step = 0;

    public function __construct(
        private readonly Options $options,
        private readonly int $nbElements,
        private int $maxSize = 20,
        private readonly string $progressChar = '█',
        private readonly string $backgroundChar = '░',
        private readonly bool $capped = true,
        private readonly Terminal|null $terminal = null,
    ) {
        if ($this->maxSize === 0) {
            $this->maxSize = $this->terminal !== null ? $this->terminal->getWidth() - 4 : 20;
        }
    }

    public function inc(int $step = 1): static
    {
        $this->step += $step;

        return $this;
    }

    public function render(string $label = '', Color|null $progress = null, Color|null $background = null): string
    {
        $percentSize = ($this->maxSize / 100);
        $percent     = 100 / $this->nbElements * $this->step;
        $size        = (int) round($percentSize * $percent);

        if ($this->capped && $size > $this->maxSize) {
            $size = $this->maxSize;
        }

        $left  = str_repeat($this->progressChar, $size);
        $right = str_repeat($this->backgroundChar, $this->maxSize - $size);
        if ($this->options->has('no-color')) {
            return '│' . $left . $right . '│ ' . $label;
        }

        $progress   ??= new Bit8RGBColor(0, 3, 0);
        $background ??= new Bit8GreyscaleColor(5);

        if ($this->backgroundChar !== ' ') {
            $right = (new Style())->color($background)->apply($right);
        } else {
            $right = (new Style())->background($background)->apply($right);
        }

        $left = (new Style())->background($progress)->color($progress)->apply($left);

        return $left . $right . ' ' . $label;
    }
}
