<?php

declare(strict_types=1);

namespace Infrangible\CatalogProductPrice\Helper;

use Magento\Framework\Pricing\Amount\AmountInterface;
use Magento\Framework\Pricing\SaleableInterface;

/**
 * @author      Andreas Knollmann
 * @copyright   Copyright (c) 2014-2024 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */
class Information extends Value
{
    protected function getSectionName(): string
    {
        return 'information';
    }

    public function getPrice(SaleableInterface $saleable): ?AmountInterface
    {
        return $this->getValue(
            $this->getSectionName(),
            'price',
            $saleable
        );
    }

    public function getData(SaleableInterface $saleable): array
    {
        $data = [];

        $label = $this->getLabel(
            $saleable,
            ''
        );

        if (! $this->variables->isEmpty($label)) {
            $data[ 'display_label' ] = $label;
        }

        $amount = $this->getPrice($saleable);

        if (! $this->variables->isEmpty($amount) && $amount instanceof AmountInterface) {
            $data[ 'amount' ] = $amount->getValue();
        }

        $information = $this->getInformation($saleable);

        if (! $this->variables->isEmpty($information)) {
            $data[ 'information' ] = $information;
        }

        return $data;
    }
}
