<?php

declare(strict_types=1);

namespace Infrangible\CatalogProductPrice\Helper;

/**
 * @author      Andreas Knollmann
 * @copyright   Copyright (c) 2014-2024 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */
class FinalPrice extends Value
{
    protected function getSectionName(): string
    {
        return 'final_price';
    }
}
