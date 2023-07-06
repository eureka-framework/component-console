<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Eureka\Component\Console\Table;

use Eureka\Component\Console\Style\CellStyle;

class Column
{
    public function __construct(
        private readonly CellStyle $style = new CellStyle()
    ) {
    }

    public function getStyle(): CellStyle
    {
        return $this->style;
    }
}
