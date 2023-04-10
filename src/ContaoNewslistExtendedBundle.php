<?php

declare(strict_types=1);

/*
 * This file is part of the Contao Newslist Extended extension.
 *
 * (c) inspiredminds
 *
 * @license LGPL-3.0-or-later
 */

namespace InspiredMinds\ContaoNewslistExtended;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class ContaoNewslistExtendedBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
