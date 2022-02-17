<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Eureka\Component\Console\Style;

use Eureka\Component\Console\Argument\Argument;

/**
 * Add style to text for unix terminal display.
 *
 * @author Romain Cottard
 */
class Style
{
    /** @var string DECORATION_NONE Index color character for no decoration. */
    const DECORATION_NONE = '0;';

    /** @var string DECORATION_BOLD Index color character for text bold decoration. */
    const DECORATION_BOLD = '1;';

    /** @var string DECORATION_UNDERLINE Index color character text underline decoration. */
    const DECORATION_UNDERLINE = '4;';

    /** @var string REGULAR_FOREGROUND Index color character normal foreground. */
    const REGULAR_FOREGROUND = '3';

    /** @var string REGULAR_BACKGROUND Index color character normal background. */
    const REGULAR_BACKGROUND = '4';

    /** @var string HIGH_FOREGROUND Index color character highlight foreground. */
    const HIGH_FOREGROUND = '9';

    /** @var string HIGH_BACKGROUND Index color character highlight background. */
    const HIGH_BACKGROUND = '10';

    /** @var string BEGIN First characters for color text. (internal constant) */
    const BEGIN = "\033[";

    /** @var string END Last characters for color text. (internal constant) */
    const END = 'm';

    /** @var string DEACTIVATE Last characters for stopping color text. (internal constant) */
    const DEACTIVATE = "\033[0m";

    /** @var string $foregroundColor Foreground color character */
    protected string $foregroundColor = Color::WHITE;

    /** @var string $foregroundColor Foreground color character */
    protected string $backgroundColor = Color::BLACK;

    /** @var string $text Text to style */
    protected string $text = '';

    /** @var bool $isUnderline If text is underlined */
    protected bool $isUnderline = false;

    /** @var bool $isBold If text is bolded */
    protected bool $isBold = false;

    /** @var bool $hasHighlightedBackground If background has highlighted color. */
    protected bool $hasHighlightedBackground = false;

    /** @var bool $hasHighlightedBackground If background has highlighted color. */
    protected bool $hasHighlightedForeground = false;

    /** @var int $padNb Pad number of char */
    protected int $padNb = 0;

    /** @var string $padChar Pad char */
    protected string $padChar = ' ';

    /** @var int $padDir Pad direction */
    protected int $padDir = STR_PAD_RIGHT;

    /** @var bool $isStyleEnabled */
    protected bool $isStyleEnabled = true;

    /**
     * Class constructor
     *
     * @param string $text
     */
    public function __construct(string $text = '')
    {
        $this->text           = $text;
        $this->isStyleEnabled = Argument::getInstance()->has('color');
    }

    /**
     * Enable / Disable underline style.
     *
     * @param bool $isUnderline
     * @return $this
     */
    public function underline(bool $isUnderline = true): self
    {
        $this->isUnderline = $isUnderline;

        return $this;
    }

    /**
     * Enable / Disable bold style.
     *
     * @param bool $isBold
     * @return $this
     */
    public function bold(bool $isBold = true): self
    {
        $this->isBold = $isBold;

        return $this;
    }

    /**
     * Enable / Disable highlight on background or foreground
     *
     * @param  string  $type
     * @param  bool $isHighlight
     * @return $this
     */
    public function highlight(string $type = 'bg', bool $isHighlight = true): self
    {
        if ($type === 'bg') {
            $this->highlightBackground($isHighlight);
        } else {
            $this->highlightForeground($isHighlight);
        }

        return $this;
    }

    /**
     * Enable / Disable highlight on background
     *
     * @param  bool $isHighlight
     * @return $this
     */
    public function highlightBackground(bool $isHighlight = true): self
    {
        $this->hasHighlightedBackground = $isHighlight;

        return $this;
    }

    /**
     * Enable / Disable highlight on background or foreground
     *
     * @param  bool $isHighlight
     * @return $this
     */
    public function highlightForeground(bool $isHighlight = true): self
    {
        $this->hasHighlightedForeground = $isHighlight;

        return $this;
    }

    /**
     * Set color for background / foreground
     *
     * @param  string $type
     * @param  string $color
     * @return $this
     */
    public function color(string $type = 'bg', string $color = Color::WHITE): self
    {
        if ($type === 'bg') {
            $this->colorBackground($color);
        } else {
            $this->colorForeground($color);
        }

        return $this;
    }

    /**
     * Set color for background
     *
     * @param  string $color
     * @return $this
     */
    public function colorBackground(string $color = Color::WHITE): self
    {
        $this->backgroundColor = $color;

        return $this;
    }

    /**
     * Set color for foreground
     *
     * @param  string $color
     * @return $this
     */
    public function colorForeground(string $color = Color::WHITE): self
    {
        $this->foregroundColor = $color;

        return $this;
    }

    /**
     * Get text with styles.
     *
     * @return string
     */
    public function get(): string
    {
        $textDisplay = $this->text;

        if ($this->padNb > 0) {
            $textDisplay = str_pad($textDisplay, $this->padNb, $this->padChar, $this->padDir);
        }

        if (!$this->isStyleEnabled) {
            return $textDisplay;
        }

        $text = '';
        if ($this->foregroundColor !== '') {
            //~ Highlight
            $highlight = $this->hasHighlightedForeground ? static::HIGH_FOREGROUND : static::REGULAR_FOREGROUND;

            //~ Decoration
            $decoration  = $this->isBold ? static::DECORATION_BOLD : '';
            $decoration .= $this->isUnderline ? static::DECORATION_UNDERLINE : '';
            $decoration  = !empty($decoration) ? $decoration : static::DECORATION_NONE;

            //~ Apply style
            $text .= self::BEGIN . $decoration . $highlight . $this->foregroundColor . self::END;
        }

        if ($this->backgroundColor !== '') {
            $highlight = $this->hasHighlightedBackground ? static::HIGH_BACKGROUND : static::REGULAR_BACKGROUND;
            $text     .= self::BEGIN . $highlight . $this->backgroundColor . self::END;
        }

        return $text . $textDisplay . self::DEACTIVATE;
    }

    /**
     * Reset styles.
     *
     * @return $this
     */
    public function reset(): self
    {
        $this->isBold                   = false;
        $this->isUnderline              = false;
        $this->hasHighlightedBackground = false;
        $this->hasHighlightedForeground = false;
        $this->backgroundColor          = Color::BLACK;
        $this->foregroundColor          = Color::WHITE;
        $this->padNb                    = 0;
        $this->padChar                  = ' ';
        $this->padDir                   = STR_PAD_RIGHT;

        return $this;
    }

    /**
     * Set text.
     *
     * @param string $text
     * @return $this
     */
    public function setText(string $text = ''): self
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Set pad for the text.
     *
     * @param  int    $pad
     * @param  string $char
     * @param  int    $dir
     * @return $this
     */
    public function pad(int $pad, string $char = ' ', int $dir = STR_PAD_RIGHT): self
    {
        $this->padNb   = $pad;
        $this->padChar = $char;
        $this->padDir  = $dir;

        return $this;
    }

    /**
     * Return text with styles.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->get();
    }
}
