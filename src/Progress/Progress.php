<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Eureka\Component\Console\Progress;

use Eureka\Component\Console\Argument;
use Eureka\Component\Console\Style\OldColor;
use Eureka\Component\Console\Style\OldStyle;
use Eureka\Component\Console\IO\Out;

/**
 * Display progression with percent & time.
 *
 * @author Romain Cottard
 */
class Progress
{
    /** @var int TYPE_BAR */
    const TYPE_BAR = 1;

    /** @var int TYPE_PERCENT */
    const TYPE_PERCENT = 2;

    /** @var int TYPE_TIME */
    const TYPE_TIME = 3;

    /** @var array<int, bool> $typesAllowed */
    protected static array $typesAllowed = [
        self::TYPE_BAR => true,
        self::TYPE_PERCENT => true,
        self::TYPE_TIME => true,
    ];

    /** @var int $type Type of display. */
    protected int $typeDisplay = self::TYPE_BAR;

    /** @var int $currentIndex Current index */
    protected int $currentIndex = 0;

    /** @var float $percentStep Percent step */
    protected float $percentStep = 0.0;

    /** @var string $globalName Name for progress */
    protected string $globalName = '';

    /** @var int $initialTime Initial time */
    protected int $initialTime = 0;

    /** @var int $elapsedTime Time elapsed */
    protected int $elapsedTime = 0;

    /** @var bool $active Whether progress bars are activated or not */
    protected bool $active = false;

    /** @var bool $completed Whether the progress bar is complete */
    protected bool $completed = false;

    /** @var bool $capped Whether the progress should display at most 100% or not */
    protected bool $capped = false;

    /**
     * Class constructor
     *
     * @param  string $globalName Name for progress
     * @param  int $nbElements Number total of elements.
     * @param  bool $capped Whether the progress should display at most 100% or not
     */
    public function __construct(string $globalName, int $nbElements, bool $capped = true)
    {
        $nbElements = $nbElements <= 0 ? 1 : $nbElements;

        $this->percentStep = 100 / $nbElements;
        $this->globalName  = (string) (new OldStyle($globalName))->highlightForeground();
        $this->active      = Argument\Argument::getInstance()->has('progress');
        $this->capped      = $capped;
    }

    /**
     * Set type display.
     *
     * @param  int $typeDisplay
     * @return self
     */
    public function setTypeDisplay(int $typeDisplay): self
    {
        if (!isset(self::$typesAllowed[$typeDisplay])) {
            throw new \DomainException('Display type is not allowed. (type: ' . $typeDisplay . ')');
        }

        $this->typeDisplay = $typeDisplay;

        return $this;
    }

    /**
     * Display progress
     *
     * @param string $label
     * @param int $increment
     * @return self
     */
    public function display(string $label, int $increment = 1): self
    {
        if (!$this->active || $this->completed) {
            return $this;
        }

        $this->currentIndex += $increment;

        $percent      = floor($this->currentIndex * $this->percentStep);
        $percent      = (float) ($this->capped ? min(100, $percent) : $percent);
        $percentExact = (float) ($this->currentIndex * $this->percentStep);

        switch ($this->typeDisplay) {
            case self::TYPE_PERCENT:
                $this->displayPercent($label, $percentExact);
                break;
            case self::TYPE_TIME:
                $this->displayTime($label, $percent, $percentExact); // @codeCoverageIgnore
                break; // @codeCoverageIgnore
            case self::TYPE_BAR:
            default:
                $this->displayBar($label, $percent);
                break;
        }

        return $this;
    }

    /**
     * Display complete progress.
     *
     * @param  string $label
     * @return void
     */
    public function displayComplete(string $label): void
    {
        $this->currentIndex = (int) (100 / $this->percentStep);
        $this->display($label);
        Out::std('');
    }

    /**
     * Display progress as bar.
     *
     * @param  string $label
     * @param  float $percent
     * @return void
     */
    private function displayBar(string $label, float $percent): void
    {
        $bar = ' [' . str_pad(str_repeat('#', (int) floor($percent / 2)), 50) . '] ' . str_pad($label, 50);
        Out::std((string) (new OldStyle())->color('fg', OldColor::GREEN)->bold()->setText($bar), "\r");
    }

    /**
     * Display progress as percent.
     *
     * @param  string $label
     * @param  float $percentExact
     * @return void
     */
    private function displayPercent(string $label, float $percentExact): void
    {
        $bar = ' [' . str_pad(number_format($percentExact, 2), 6, ' ', STR_PAD_LEFT) . '%] ' . str_pad($label, 50);
        Out::std((string) (new OldStyle())->color('fg', OldColor::GREEN)->bold()->setText($bar), "\r");
    }

    /**
     * Display progress
     *
     * @param  string $label Processing element name (for display)
     * @param  float $percent
     * @param  float $percentExact
     * @return void
     * @codeCoverageIgnore
     */
    private function displayTime(string $label, float $percent, float $percentExact): void
    {
        if ($this->initialTime == 0) {
            $this->initialTime = time();
        } else {
            $this->elapsedTime = time() - $this->initialTime;
        }

        $style       = new OldStyle(str_pad((string) $percent, 3, ' ', STR_PAD_LEFT) . '%');
        $percentText = (string) $style->color('fg', OldColor::GREEN)->highlight('fg');

        $timeDoneText = '';
        $timeLeftText = '';
        if ($this->elapsedTime != 0) {
            $timeDoneText = (string) $style->reset()
                ->setText(str_pad((string) $this->elapsedTime, 5, ' ', STR_PAD_LEFT))
                ->color('fg', OldColor::GREEN)
                ->highlight('fg');
            $timeLeft     = round((($this->elapsedTime * 100) / $percentExact)) - $this->elapsedTime;
            $timeLeftText = (string) $style->reset()
                ->setText(str_pad((string)$timeLeft, 5, ' ', STR_PAD_LEFT))
                ->color('fg', OldColor::GREEN)
                ->highlight('fg');
        }

        $label = str_pad($label, 80);
        Out::std("  > $this->globalName - [$percentText] [$timeDoneText/$timeLeftText sec] - $label", "\r");

        if ($percent >= 100) {
            $this->completed = true;
            $label =  str_pad('done !', 80);
            Out::std("  > $this->globalName - [$percentText] - $label");
        }
    }

    /**
     * Interrupt progress
     *
     * @codeCoverageIgnore
     */
    public function interrupt(): void
    {
        if (!$this->active || $this->completed) {
            return;
        }

        $percent     = floor($this->currentIndex * $this->percentStep);
        $style       = new OldStyle(str_pad((string) $percent, 3, ' ', STR_PAD_LEFT) . '%');
        $percentText = (string) $style->color('fg', OldColor::GREEN)->highlight('fg');

        $this->completed = true;
        Out::std('  > ' . $this->globalName . ' - [' . $percentText . '] - ' . str_pad('stopped !', 80));
    }
}
