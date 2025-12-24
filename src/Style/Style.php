<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Eureka\Component\Console\Style;

use Eureka\Component\Console\Color\Bit24Color;
use Eureka\Component\Console\Color\Bit4Color;
use Eureka\Component\Console\Color\Bit4StandardColor;
use Eureka\Component\Console\Color\Bit8Color;
use Eureka\Component\Console\Color\Color;
use Eureka\Component\Console\Option\Options;
use Eureka\Component\Console\Terminal\Terminal;

/**
 * Add style to text for unix terminal display.
 *
 * @author Romain Cottard
 */
class Style
{
    protected ?Color $fgColor = null;
    protected ?Color $bgColor = null;
    protected bool $bold = false;
    protected bool $faint = false;
    protected bool $italic = false;
    protected bool $underline = false;
    protected bool $blink = false;
    protected bool $fastBlink = false;
    protected bool $invert = false;
    protected bool $strike = false;

    protected bool $noColor = false;

    public function __construct(?Options $options = null)
    {
        if (
            ($options !== null && $options->has('no-color') && $options->get('no-color')->getArgument())
            || !empty(getenv('NO_COLOR'))
        ) {
            $this->noColor = true;
        }
    }

    public function color(Color $color): static
    {
        $this->fgColor = $color;

        return $this;
    }

    public function background(Color $color): static
    {
        $this->bgColor = $color;

        return $this;
    }

    public function bold(bool $bold = true): static
    {
        $this->bold = $bold;

        return $this;
    }

    public function faint(bool $faint = true): static
    {
        $this->faint = $faint;

        return $this;
    }

    public function italic(bool $italic = true): static
    {
        $this->italic = $italic;

        return $this;
    }

    public function underline(bool $underline = true): static
    {
        $this->underline = $underline;

        return $this;
    }

    public function blink(bool $blink = true, bool $fast = false): static
    {
        $this->blink     = $blink;
        $this->fastBlink = $fast;

        return $this;
    }

    public function invert(bool $invert = true): static
    {
        $this->invert = $invert;

        return $this;
    }

    public function strike(bool $strike = true): static
    {
        $this->strike = $strike;

        return $this;
    }

    public function apply(string|\Stringable|int|float|null $text): string
    {
        $csi = Terminal::CSI;

        $styledText = (string) $text;

        if ($this->bold) {
            $styledText = "{$csi}1m{$styledText}";
        }

        if ($this->faint) {
            $styledText = "{$csi}2m{$styledText}";
        }

        if ($this->italic) {
            $styledText = "{$csi}3m{$styledText}";
        }

        if ($this->underline) {
            $styledText = "{$csi}4m{$styledText}";
        }

        if ($this->blink && !$this->fastBlink) {
            $styledText = "{$csi}5m{$styledText}";
        } elseif ($this->blink) {
            $styledText = "{$csi}6m{$styledText}";
        }

        if ($this->invert) {
            $styledText = "{$csi}7m{$styledText}";
        }

        if ($this->strike) {
            $styledText = "{$csi}9m{$styledText}";
        }

        if (!$this->noColor) {
            $styledText = $this->applyColor($styledText);
            $styledText = $this->applyBackground($styledText);
        }

        if ($styledText !== $text) {
            $styledText = "{$styledText}{$csi}0m";
        }

        return $styledText;
    }

    private function applyColor(string $styledText): string
    {
        $csi = Terminal::CSI;

        if ($this->fgColor instanceof Bit4Color) {
            $prefix = $this->fgColor instanceof Bit4StandardColor ? 3 : 9;
            $styledText = "{$csi}{$prefix}{$this->fgColor->getIndex()}m{$styledText}";
        } elseif ($this->fgColor instanceof Bit8Color) {
            $styledText = "{$csi}38;5;{$this->fgColor->getIndex()}m{$styledText}";
        } elseif ($this->fgColor instanceof Bit24Color) {
            [$r, $g, $b] = $this->fgColor->rgb();
            $styledText = "{$csi}38;2;{$r};{$g};{$b}m{$styledText}";
        }

        return $styledText;
    }

    private function applyBackground(string $styledText): string
    {
        $csi = Terminal::CSI;

        if ($this->bgColor instanceof Bit4Color) {
            $prefix = $this->bgColor instanceof Bit4StandardColor ? 4 : 10;
            $styledText = "{$csi}{$prefix}{$this->bgColor->getIndex()}m{$styledText}";
        } elseif ($this->bgColor instanceof Bit8Color) {
            $styledText = "{$csi}48;5;{$this->bgColor->getIndex()}m{$styledText}";
        } elseif ($this->bgColor instanceof Bit24Color) {
            [$r, $g, $b] = $this->bgColor->rgb();
            $styledText = "{$csi}48;2;{$r};{$g};{$b}m{$styledText}";
        }

        return $styledText;
    }
}
