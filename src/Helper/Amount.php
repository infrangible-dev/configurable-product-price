<?php

declare(strict_types=1);

namespace Infrangible\CatalogProductPrice\Helper;

use FeWeDev\Base\Variables;

/**
 * @author      Andreas Knollmann
 * @copyright   Copyright (c) 2014-2024 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */
class Amount
{
    /** @var Variables */
    protected $variables;

    /** @var FinalPrice */
    protected $finalPriceHelper;

    /** @var OldPrice */
    protected $oldPriceHelper;

    /** @var MinPrice */
    protected $minPriceHelper;

    /** @var MaxPrice */
    protected $maxPriceHelper;

    public function __construct(
        Variables $variables,
        FinalPrice $priceHelper,
        OldPrice $oldPriceHelper,
        MinPrice $minPriceHelper,
        MaxPrice $maxPriceHelper
    ) {
        $this->variables = $variables;
        $this->finalPriceHelper = $priceHelper;
        $this->oldPriceHelper = $oldPriceHelper;
        $this->minPriceHelper = $minPriceHelper;
        $this->maxPriceHelper = $maxPriceHelper;
    }

    public function getInformation(\Magento\Framework\Pricing\Render\Amount $amount): string
    {
        $priceType = $amount->getData('price_type');

        if ($priceType === 'finalPrice') {
            $information = $this->finalPriceHelper->getInformation($amount->getSaleableItem());
        } elseif ($priceType === 'oldPrice') {
            $information = $this->oldPriceHelper->getInformation($amount->getSaleableItem());
        } elseif ($priceType === 'minPrice') {
            $information = $this->minPriceHelper->getInformation($amount->getSaleableItem());
        } elseif ($priceType === 'maxPrice') {
            $information = $this->maxPriceHelper->getInformation($amount->getSaleableItem());
        } else {
            $information = '';
        }

        return $information === null ? '' : $this->variables->stringValue($information);
    }
}
