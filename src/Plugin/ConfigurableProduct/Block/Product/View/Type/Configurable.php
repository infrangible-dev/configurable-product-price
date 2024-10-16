<?php

declare(strict_types=1);

namespace Infrangible\CatalogProductPrice\Plugin\ConfigurableProduct\Block\Product\View\Type;

use FeWeDev\Base\Json;
use FeWeDev\Base\Variables;
use Infrangible\CatalogProductPrice\Helper\FinalPrice;
use Infrangible\CatalogProductPrice\Helper\Information;
use Infrangible\CatalogProductPrice\Helper\OldPrice;
use Infrangible\CatalogProductPrice\Helper\Value;
use Magento\Catalog\Model\Product;
use Magento\Framework\Pricing\Amount\AmountInterface;

/**
 * @author      Andreas Knollmann
 * @copyright   Copyright (c) 2014-2024 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */
class Configurable
{
    /** @var Json */
    protected $json;

    /** @var Variables */
    protected $variables;

    /** @var FinalPrice */
    protected $finalPriceHelper;

    /** @var OldPrice */
    protected $oldPriceHelper;

    /** @var Information */
    protected $informationHelper;

    public function __construct(
        FinalPrice $finalPriceHelper,
        Information $informationHelper,
        Json $json,
        OldPrice $oldPriceHelper,
        Variables $variables
    ) {
        $this->finalPriceHelper = $finalPriceHelper;
        $this->informationHelper = $informationHelper;
        $this->json = $json;
        $this->oldPriceHelper = $oldPriceHelper;
        $this->variables = $variables;
    }

    public function afterGetJsonConfig(
        \Magento\ConfigurableProduct\Block\Product\View\Type\Configurable $subject,
        string $result
    ): string {
        $config = $this->json->decode($result);

        $product = $subject->getProduct();

        $this->updateFinalPrice(
            $config,
            $product
        );

        $this->updateOldPrice(
            $config,
            $product
        );

        $this->addInformation(
            $config,
            $product
        );

        foreach ($subject->getAllowProducts() as $product) {
            $this->updateOptionFinalPrice(
                $config,
                $product
            );

            $this->updateOptionOldPrice(
                $config,
                $product
            );

            $this->addOptionInformation(
                $config,
                $product
            );
        }

        return $this->json->encode($config);
    }

    private function updateFinalPrice(array &$config, Product $product): void
    {
        $this->updatePrice(
            $config,
            $product,
            $this->finalPriceHelper,
            'finalPrice'
        );
    }

    private function updateOldPrice(array &$config, Product $product): void
    {
        $this->updatePrice(
            $config,
            $product,
            $this->oldPriceHelper,
            'oldPrice'
        );
    }

    private function updatePrice(array &$config, Product $product, Value $valueHelper, string $priceType): void
    {
        $displayLabel = $valueHelper->getLabel(
            $product,
            ''
        );

        if (! $this->variables->isEmpty($displayLabel)) {
            $config[ 'prices' ][ $priceType ][ 'displayLabel' ] = $displayLabel;
        }

        $information = $valueHelper->getInformation($product);

        if (! $this->variables->isEmpty($information)) {
            $config[ 'prices' ][ $priceType ][ 'information' ] = $information;
        }
    }

    private function addInformation(array &$config, Product $product): void
    {
        $displayLabel = $this->informationHelper->getLabel(
            $product,
            ''
        );

        if (! $this->variables->isEmpty($displayLabel)) {
            $config[ 'prices' ][ 'priceInformation' ][ 'displayLabel' ] = $displayLabel;
        }

        $price = $this->informationHelper->getPrice($product);

        if (! $this->variables->isEmpty($price) && $price instanceof AmountInterface) {
            $config[ 'prices' ][ 'priceInformation' ][ 'price' ] = $price->getValue();
        }

        $information = $this->informationHelper->getInformation($product);

        if (! $this->variables->isEmpty($information)) {
            $config[ 'prices' ][ 'priceInformation' ][ 'information' ] = $information;
        }
    }

    private function updateOptionFinalPrice(array &$config, Product $product): void
    {
        $this->updateOptionPrice(
            $config,
            $product,
            $this->finalPriceHelper,
            'finalPrice'
        );
    }

    private function updateOptionOldPrice(array &$config, Product $product): void
    {
        $this->updateOptionPrice(
            $config,
            $product,
            $this->oldPriceHelper,
            'oldPrice'
        );
    }

    private function updateOptionPrice(array &$config, Product $product, Value $valueHelper, string $priceType): void
    {
        $productId = $product->getId();

        $displayLabel = $valueHelper->getLabel(
            $product,
            ''
        );

        if (! $this->variables->isEmpty($displayLabel)) {
            $config[ 'optionPrices' ][ $productId ][ $priceType ][ 'displayLabel' ] = $displayLabel;
        }

        $information = $valueHelper->getInformation($product);

        if (! $this->variables->isEmpty($information)) {
            $config[ 'optionPrices' ][ $productId ][ $priceType ][ 'information' ] = $information;
        }
    }

    private function addOptionInformation(array &$config, Product $product): void
    {
        $productId = $product->getId();

        $displayLabel = $this->informationHelper->getLabel(
            $product,
            ''
        );

        if (! $this->variables->isEmpty($displayLabel)) {
            $config[ 'optionPrices' ][ $productId ][ 'priceInformation' ][ 'displayLabel' ] = $displayLabel;
        }

        $price = $this->informationHelper->getPrice($product);

        if (! $this->variables->isEmpty($price) && $price instanceof AmountInterface) {
            $config[ 'optionPrices' ][ $productId ][ 'priceInformation' ][ 'price' ] = $price->getValue();
        }

        $information = $this->informationHelper->getInformation($product);

        if (! $this->variables->isEmpty($information)) {
            $config[ 'optionPrices' ][ $productId ][ 'priceInformation' ][ 'information' ] = $information;
        }
    }
}
