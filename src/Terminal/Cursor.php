<?php

/*
 * Copyright (c) Deezer
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Eureka\Component\Console\Terminal;

use Eureka\Component\Console\Input\StreamInput;
use Eureka\Component\Console\Output\Output;
use Eureka\Component\Console\Output\StreamOutput;

/**
 * Handle cursor on terminal
 *
 * @author Romain Cottard
 * @link https://en.wikipedia.org/wiki/ANSI_escape_code
 */
class Cursor
{
    /**
     * @param resource $inputStream
     */
    public function __construct(
        private readonly Output $output,
        private readonly Shell $shell = new Shell(),
        private readonly mixed $inputStream = \STDIN
    ) {
        if (!is_resource($this->inputStream)) {
            throw new \InvalidArgumentException('Input stream must be a resource');
        }
    }

    public function up(int $lines = 1): static
    {
        return $this->execute($lines . 'A');
    }

    public function down(int $lines = 1): static
    {
        return $this->execute($lines . 'B');
    }

    public function right(int $columns = 1): static
    {
        return $this->execute($columns . 'C');
    }

    public function left(int $columns = 1): static
    {
        return $this->execute($columns . 'D');
    }

    public function lineDown(int $line = 1): static
    {
        return $this->execute($line . 'E');
    }

    public function lineUp(int $line = 1): static
    {
        return $this->execute($line . 'F');
    }

    public function column(int $column = 1): static
    {
        return $this->execute($column . 'G');
    }

    public function to(int $line = 1, int $column = 1): static
    {
        return $this->execute($line . ';' . $column . 'H');
    }

    /**
     * @param int $mode 0: from cursor to end, 1: from cursor to beginning, 2: all, 3: all + scroll back buffer
     * @return static
     */
    public function clear(int $mode = 2): static
    {
        return $this->execute($mode  . 'J');
    }

    /**
     * @param int $mode 0: from cursor to end of line, 1: from cursor to beginning of line, 2: entire line
     * @return static
     */
    public function clearLine(int $mode = 2): static
    {
        return $this->execute($mode  . 'K');
    }

    public function scrollUp(int $page = 1): static
    {
        return $this->execute($page  . 'S');
    }

    public function scrollDown(int $page = 1): static
    {
        return $this->execute($page  . 'T');
    }

    public function save(): static
    {
        return $this->execute('s');
    }

    public function restore(): static
    {
        return $this->execute('u');
    }

    public function show(): static
    {
        return $this->execute('?25h');
    }

    public function hide(): static
    {
        return $this->execute('?25l');
    }

    /**
     * @return array{int, int}
     * @codeCoverageIgnore
     */
    public function position(): array
    {
        //~ Store current mode to restore it after
        $mode = (string) $this->shell->exec('stty -g');

        if (empty($mode)) {
            return [1, 1];
        }

        //~ enable control chars & enable displaying it that will avoid any display on terminal
        $this->shell->exec('stty -icanon -echo');

        //~ Write on input the following command will return the cursor position in this format "\033[{LINE};{COL}R"
        (new StreamOutput($this->inputStream, false))->write(Terminal::CSI . '6n');

        //~ Read returned code from previous command on input stream
        $code = (string) (new StreamInput($this->inputStream))->readline();

        //~ Restore the stty to original mode
        $this->shell->exec("stty $mode");

        //~ Parse returned response code
        \sscanf($code, Terminal::CSI . '%d;%dR', $line, $col);

        return [(int) $col, (int) $line];
    }

    private function execute(string $command): static
    {
        $this->output->write(Terminal::CSI . $command);
        return $this;
    }
}
