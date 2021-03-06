<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Eureka\Component\Console\Tests;

use Eureka\Component\Console\Argument\Argument;
use Eureka\Component\Console\Style\Color;
use Eureka\Component\Console\Style\Style;
use PHPUnit\Framework\TestCase;

/**
 * Class Test for Style.
 *
 * @author Romain Cottard
 * @group unit
 */
class StyleTest extends TestCase
{
    /**
     * Test Style class.
     * Simulate --color argument for script.
     */
    public function testTextHasBoldCharactersWhenColorIsEnabled()
    {
        //~ --color parameter is required to render text with "style characters".
        $mockParameterColor = array('--color');
        Argument::getInstance()->parse($mockParameterColor);

        //~ Set style
        $style = (new Style())->bold();

        $this->assertEquals("\033[1;37m\033[40mThis is my text\033[0m", (string) $style->setText('This is my text'));
    }

    /**
     * Test Style class.
     * Simulate --color argument for script.
     */
    public function testTextHasUnderlineCharactersWhenColorIsEnabled()
    {
        //~ --color parameter is required to render text with "style characters".
        $mockParameterColor = array('--color');
        Argument::getInstance()->parse($mockParameterColor);

        //~ Set style
        $style = (new Style())->underline();

        $this->assertEquals("\033[4;37m\033[40mThis is my text\033[0m", (string) $style->setText('This is my text'));
    }

    /**
     * Test Style class.
     * Simulate --color argument for script.
     */
    public function testTextHasBoldAndUnderlineCharactersWhenColorIsEnabled()
    {
        //~ --color parameter is required to render text with "style characters".
        $mockParameterColor = array('--color');
        Argument::getInstance()->parse($mockParameterColor);

        //~ Set style
        $style = (new Style())->bold()->underline() ;

        $this->assertEquals("\033[1;4;37m\033[40mThis is my text\033[0m", (string) $style->setText('This is my text'));
    }

    /**
     * Test Style class.
     * Simulate --color argument for script.
     */
    public function testTextHasBackgroundAndForegroundColorsCharactersWhenColorIsEnabled()
    {
        //~ --color parameter is required to render text with "style characters".
        $mockParameterColor = array('--color');
        Argument::getInstance()->parse($mockParameterColor);

        //~ Set style
        $style = (new Style())->color('bg', Color::GREEN)->color('fg', Color::BLACK);

        $this->assertEquals("\033[0;30m\033[42mThis is my text\033[0m", (string) $style->setText('This is my text'));
    }

    /**
     * Test Style class.
     * Simulate --color argument for script.
     */
    public function testTextHasBoldAndUnderlineAndForegroundColorCharactersWhenColorIsEnabled()
    {
        //~ --color parameter is required to render text with "style characters".
        $mockParameterColor = array('--color');
        Argument::getInstance()->parse($mockParameterColor);

        //~ Set style
        $style = (new Style())->color('fg', Color::RED)->bold()->underline();

        $this->assertEquals("\033[1;4;31m\033[40mThis is my text\033[0m", (string) $style->setText('This is my text'));
    }

    /**
     * Test Style class.
     * Simulate --color argument for script.
     */
    public function testTextHasUnderlineAndForegroundColorCharactersWhenColorIsEnabled()
    {
        //~ --color parameter is required to render text with "style characters".
        $mockParameterColor = array('--color');
        Argument::getInstance()->parse($mockParameterColor);

        //~ Set style
        $style = (new Style())->color('fg', Color::RED)->bold(false)->underline();

        $this->assertEquals("\033[4;31m\033[40mThis is my text\033[0m", (string) $style->setText('This is my text'));
    }

    /**
     * Test Style class.
     * Simulate --color argument for script.
     */
    public function testTextHasBoldAndForegroundHighlightAndForegroundColorCharactersWhenColorIsEnabled()
    {
        //~ --color parameter is required to render text with "style characters".
        $mockParameterColor = array('--color');
        Argument::getInstance()->parse($mockParameterColor);

        //~ Set style
        $style = (new Style())->color('fg', Color::RED)->bold()->highlight('fg');

        $this->assertEquals("\033[1;91m\033[40mThis is my text\033[0m", (string) $style->setText('This is my text'));
    }

    /**
     * Test Style class.
     * Simulate --color argument for script.
     */
    public function testTextHasBackgroundHighlightAndBackgroundColorCharactersWhenColorIsEnabled()
    {
        //~ --color parameter is required to render text with "style characters".
        $mockParameterColor = array('--color');
        Argument::getInstance()->parse($mockParameterColor);

        //~ Set style
        $style = (new Style())->color('bg', Color::YELLOW)->highlight('bg');

        $this->assertEquals("\033[0;37m\033[103mThis is my text\033[0m", (string) $style->setText('This is my text'));
    }

    /**
     * Test Style class.
     * Simulate --color argument for script.
     */
    public function testTextHasForegroundHighlightAndBackgroundColorCharactersWhenColorIsEnabled()
    {
        //~ --color parameter is required to render text with "style characters".
        $mockParameterColor = array('--color');
        Argument::getInstance()->parse($mockParameterColor);

        //~ Set style
        $style = (new Style())->color('bg', Color::YELLOW)->highlight('fg');

        $this->assertEquals("\033[0;97m\033[43mThis is my text\033[0m", (string) $style->setText('This is my text'));
    }

    /**
     * Test Style class.
     */
    public function testTextHasNotBoldCharactersWhenColorIsNotEnabled()
    {
        //~ --color parameter is required to render text with "style characters".
        $mockParameterColor = array();
        Argument::getInstance()->parse($mockParameterColor);

        //~ Set style
        $style = (new Style())->bold();

        $this->assertEquals('This is my text', (string) $style->setText('This is my text'));
    }

    /**
     * Test Style class.
     */
    public function testTextHasNotUnderlineCharactersWhenColorIsNotEnabled()
    {
        //~ --color parameter is required to render text with "style characters".
        $mockParameterColor = array();
        Argument::getInstance()->parse($mockParameterColor);

        //~ Set style
        $style = (new Style())->underline();

        $this->assertEquals('This is my text', (string) $style->setText('This is my text'));
    }

    /**
     * Test Style class.
     */
    public function testTextHasNotBoldAndUnderlineCharactersWhenColorIsNotEnabled()
    {
        //~ --color parameter is required to render text with "style characters".
        $mockParameterColor = array();
        Argument::getInstance()->parse($mockParameterColor);

        //~ Set style
        $style = (new Style())->bold()->underline() ;

        $this->assertEquals('This is my text', (string) $style->setText('This is my text'));
    }

    /**
     * Test Style class.
     */
    public function testTextHasNotBackgroundAndForegroundColorsCharactersWhenColorIsNotEnabled()
    {
        //~ --color parameter is required to render text with "style characters".
        $mockParameterColor = array();
        Argument::getInstance()->parse($mockParameterColor);

        //~ Set style
        $style = (new Style())->color('bg', Color::GREEN)->color('fg', Color::BLACK);

        $this->assertEquals('This is my text', (string) $style->setText('This is my text'));
    }

    /**
     * Test Style class.
     */
    public function testTextHasNotBoldAndUnderlineAndForegroundColorCharactersWhenColorIsNotEnabled()
    {
        //~ --color parameter is required to render text with "style characters".
        $mockParameterColor = array();
        Argument::getInstance()->parse($mockParameterColor);

        //~ Set style
        $style = (new Style())->color('fg', Color::RED)->bold()->underline();

        $this->assertEquals('This is my text', (string) $style->setText('This is my text'));
    }

    /**
     * Test Style class.
     */
    public function testTextHasNotUnderlineAndForegroundColorCharactersWhenColorIsNotEnabled()
    {
        //~ --color parameter is required to render text with "style characters".
        $mockParameterColor = array();
        Argument::getInstance()->parse($mockParameterColor);

        //~ Set style
        $style = (new Style())->color('fg', Color::RED)->bold(false)->underline();

        $this->assertEquals('This is my text', (string) $style->setText('This is my text'));
    }

    /**
     * Test Style class.
     */
    public function testTextHasNotBoldAndForegroundHighlightAndForegroundColorCharactersWhenColorIsNotEnabled()
    {
        //~ --color parameter is required to render text with "style characters".
        $mockParameterColor = array();
        Argument::getInstance()->parse($mockParameterColor);

        //~ Set style
        $style = (new Style())->color('fg', Color::RED)->bold()->highlight('fg');

        $this->assertEquals('This is my text', (string) $style->setText('This is my text'));
    }

    /**
     * Test Style class.
     */
    public function testTextHasNotBackgroundHighlightAndBackgroundColorCharactersWhenColorIsNotEnabled()
    {
        //~ --color parameter is required to render text with "style characters".
        $mockParameterColor = array();
        Argument::getInstance()->parse($mockParameterColor);

        //~ Set style
        $style = (new Style())->color('bg', Color::YELLOW)->highlight('bg');

        $this->assertEquals('This is my text', (string) $style->setText('This is my text'));
    }

    /**
     * Test Style class.
     */
    public function testTextHasNotForegroundHighlightAndBackgroundColorCharactersWhenColorIsNotEnabled()
    {
        //~ --color parameter is required to render text with "style characters".
        $mockParameterColor = array();
        Argument::getInstance()->parse($mockParameterColor);

        //~ Set style
        $style = (new Style())->color('bg', Color::YELLOW)->highlight('fg');

        $this->assertEquals('This is my text', (string) $style->setText('This is my text'));
    }

    /**
     * Test Style class.
     */
    public function testTextHasDefinedPaddingChars()
    {
        //~ --color parameter is required to render text with "style characters".
        $mockParameterColor = array();
        Argument::getInstance()->parse($mockParameterColor);

        //~ Set style
        $style = (new Style())->pad(8, '@');

        $this->assertEquals('Hello@@@', (string) $style->setText('Hello'));
    }
}
