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
use Eureka\Component\Console\Terminal\Terminal;

/**
 * Add style to text for unix terminal display.
 *
 * @author Romain Cottard
 */
class Style
{
    private Color|null $fgColor = null;
    private Color|null $bgColor = null;
    private bool $bold = false;
    private bool $faint = false;
    private bool $italic = false;
    private bool $underline = false;
    private bool $blink = false;
    private bool $fastBlink = false;
    private bool $invert = false;
    private bool $strike = false;

    public function color(Color $color): self
    {
        $this->fgColor = $color;

        return $this;
    }

    public function background(Color $color): self
    {
        $this->bgColor = $color;

        return $this;
    }

    public function bold(bool $bold = true): self
    {
        $this->bold = $bold;

        return $this;
    }

    public function faint(bool $faint = true): self
    {
        $this->faint = $faint;

        return $this;
    }

    public function italic(bool $italic = true): self
    {
        $this->italic = $italic;

        return $this;
    }

    public function underline(bool $underline = true): self
    {
        $this->underline = $underline;

        return $this;
    }

    public function blink(bool $blink = true, bool $fast = false): self
    {
        $this->blink     = $blink;
        $this->fastBlink = $fast;

        return $this;
    }

    public function invert(bool $invert = true): self
    {
        $this->invert = $invert;

        return $this;
    }

    public function strike(bool $strike = true): self
    {
        $this->strike = $strike;

        return $this;
    }

    public function apply(string|\Stringable|int|float|null $text): string
    {
        $csi = Terminal::CSI;

        $styledText = $text;

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

        if ($this->fgColor instanceof Bit4Color) {
            $prefix = $this->fgColor instanceof Bit4StandardColor ? 3 : 9;
            $styledText = "{$csi}{$prefix}{$this->fgColor->getIndex()}m{$styledText}";
        } elseif ($this->fgColor instanceof Bit8Color) {
            $styledText = "{$csi}38;5;{$this->fgColor->getIndex()}m{$styledText}";
        } elseif ($this->fgColor instanceof Bit24Color) {
            [$r, $g, $b] = $this->fgColor->rgb();
            $styledText = "{$csi}38;2;{$r};{$g};{$b}m{$styledText}";
        }

        if ($this->bgColor instanceof Bit4Color) {
            $prefix = $this->bgColor instanceof Bit4StandardColor ? 4 : 10;
            $styledText = "{$csi}{$prefix}{$this->bgColor->getIndex()}m{$styledText}";
        } elseif ($this->bgColor instanceof Bit8Color) {
            $styledText = "{$csi}48;5;{$this->bgColor->getIndex()}m{$styledText}";
        } elseif ($this->bgColor instanceof Bit24Color) {
            [$r, $g, $b] = $this->bgColor->rgb();
            $styledText = "{$csi}48;2;{$r};{$g};{$b}m{$styledText}";
        }

        return "{$styledText}{$csi}0m";
    }
}
