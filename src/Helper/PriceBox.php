<?php

declare(strict_types=1);

namespace Infrangible\CatalogProductPrice\Helper;

use FeWeDev\Base\Arrays;
use FeWeDev\Base\Variables;
use Infrangible\Core\Helper\Block;

/**
 * @author      Andreas Knollmann
 * @copyright   Copyright (c) 2014-2024 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */
class PriceBox
{
    /** @var Arrays */
    protected $arrays;

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

    /** @var Information */
    protected $informationHelper;

    /** @var Block */
    protected $blockHelper;

    public function __construct(
        Arrays $arrays,
        Variables $variables,
        FinalPrice $priceHelper,
        OldPrice $oldPriceHelper,
        MinPrice $minPriceHelper,
        MaxPrice $maxPriceHelper,
        Information $informationHelper,
        Block $blockHelper
    ) {
        $this->arrays = $arrays;
        $this->variables = $variables;
        $this->finalPriceHelper = $priceHelper;
        $this->oldPriceHelper = $oldPriceHelper;
        $this->minPriceHelper = $minPriceHelper;
        $this->maxPriceHelper = $maxPriceHelper;
        $this->informationHelper = $informationHelper;
        $this->blockHelper = $blockHelper;
    }

    public function updateDisplayLabel(\Magento\Framework\Pricing\Render\PriceBox $priceBox, array $arguments): array
    {
        $displayLabel = $this->arrays->getValue(
            $arguments,
            'display_label',
            ''
        );
        $priceType = $this->arrays->getValue(
            $arguments,
            'price_type'
        );

        if ($priceType === 'finalPrice') {
            $displayLabel = $this->finalPriceHelper->getLabel(
                $priceBox->getSaleableItem(),
                $displayLabel
            );
        } elseif ($priceType === 'oldPrice') {
            $displayLabel = $this->oldPriceHelper->getLabel(
                $priceBox->getSaleableItem(),
                $displayLabel
            );
        } elseif ($priceType === 'minPrice') {
            $displayLabel = $this->minPriceHelper->getLabel(
                $priceBox->getSaleableItem(),
                $displayLabel
            );
        } elseif ($priceType === 'maxPrice') {
            $displayLabel = $this->maxPriceHelper->getLabel(
                $priceBox->getSaleableItem(),
                $displayLabel
            );
        }

        $arguments[ 'display_label' ] = $displayLabel;

        return $arguments;
    }

    public function getInformationHtml(\Magento\Framework\Pricing\Render\PriceBox $priceBox): string
    {
        if ($priceBox->getData('price_type_code') !== 'final_price' && $priceBox->getData('zone') !== 'item_list') {
            return '';
        }

        $templateData = $this->informationHelper->getData($priceBox->getSaleableItem());

        if ($this->variables->isEmpty($templateData)) {
            return '';
        }

        $templateData[ 'include_container' ] = true;

        return $this->blockHelper->renderTemplateExtendedBlock(
            $priceBox,
            \Infrangible\CatalogProductPrice\Block\Information::class,
            'Infrangible_CatalogProductPrice::information.phtml',
            $templateData
        );
    }
}