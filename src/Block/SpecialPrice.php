<?php

declare(strict_types=1);

namespace Infrangible\CatalogProductPrice\Block;

use Magento\ConfigurableProduct\Pricing\Render\FinalPriceBox;
use Magento\Framework\Pricing\Amount\AmountInterface;
use Magento\Framework\Pricing\Price\PriceInterface;
use Magento\Framework\View\Element\Template;

/**
 * @author      Andreas Knollmann
 * @copyright   Copyright (c) 2014-2024 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */
class SpecialPrice extends Template
{
    public function getPriceBox(): FinalPriceBox
    {
        return $this->_getData('price_box');
    }

    public function getPriceType($priceCode): PriceInterface
    {
        return $this->getPriceBox()->getPriceType($priceCode);
    }

    public function renderAmount(AmountInterface $amount, array $arguments = []): string
    {
        return $this->getPriceBox()->renderAmount(
            $amount,
            $arguments
        );
    }

    public function getPriceId($defaultPrefix = null, $defaultSuffix = null): string
    {
        return $this->getPriceBox()->getPriceId(
            $defaultPrefix,
            $defaultSuffix
        );
    }

    public function getIdSuffix(): ?string
    {
        return $this->getPriceBox()->getData('id_suffix');
    }
}
