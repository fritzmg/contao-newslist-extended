<?php

declare(strict_types=1);

/*
 * (c) INSPIRED MINDS
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
