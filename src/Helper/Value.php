<?php

declare(strict_types=1);

namespace Infrangible\CatalogProductPrice\Helper;

use Exception;
use FeWeDev\Base\Variables;
use Infrangible\Core\Helper\Attribute;
use Infrangible\Core\Helper\Stores;
use Magento\Catalog\Model\Product;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Phrase;
use Magento\Framework\Pricing\Amount\AmountFactory;
use Magento\Framework\Pricing\SaleableInterface;

/**
 * @author      Andreas Knollmann
 * @copyright   Copyright (c) 2014-2024 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */
abstract class Value extends AbstractHelper
{
    /** @var Variables */
    protected $variables;

    /** @var Stores */
    protected $storeHelper;

    /** @var Attribute */
    protected $attributeHelper;

    /** @var AmountFactory */
    protected $amountFactory;

    public function __construct(
        Context $context,
        Variables $variables,
        Stores $storeHelper,
        Attribute $attributeHelper,
        AmountFactory $amountFactory
    ) {
        parent::__construct($context);

        $this->variables = $variables;
        $this->storeHelper = $storeHelper;
        $this->attributeHelper = $attributeHelper;
        $this->amountFactory = $amountFactory;
    }

    abstract protected function getSectionName(): string;

    public function isSectionEnabled(string $sectionName): bool
    {
        return (bool)$this->storeHelper->getStoreConfigFlag(
            sprintf(
                'infrangible_catalogproductprice/%s/enable',
                $sectionName
            )
        );
    }

    /**
     * @param Phrase|string $defaultValue
     */
    public function getLabel(SaleableInterface $saleableItem, $defaultValue): ?string
    {
        return $this->variables->stringValue(
            $this->getValue(
                $this->getSectionName(),
                'label',
                $saleableItem,
                $defaultValue
            )
        );
    }

    public function getInformation(SaleableInterface $saleableItem): ?string
    {
        return $this->variables->stringValue(
            $this->getValue(
                $this->getSectionName(),
                'information',
                $saleableItem,
                ''
            )
        );
    }

    public function isValueEnabled(string $sectionName, string $type): bool
    {
        return (bool)$this->storeHelper->getStoreConfigFlag(
            sprintf(
                'infrangible_catalogproductprice/%s/%s/enable',
                $sectionName,
                $type
            )
        );
    }

    /**
     * @param mixed $defaultValue
     */
    public function getValue(
        string $sectionName,
        string $type,
        SaleableInterface $saleableItem,
        $defaultValue = null
    ) {
        if (! $this->isSectionEnabled($sectionName) || ! $this->isValueEnabled(
                $sectionName,
                $type
            )) {

            return $defaultValue;
        }

        if (! ($saleableItem instanceof Product)) {
            return $defaultValue;
        }

        $attributeId = $this->storeHelper->getStoreConfig(
            sprintf(
                'infrangible_catalogproductprice/%s/%s/attribute_id',
                $sectionName,
                $type
            )
        );

        try {
            $attribute = $this->attributeHelper->getAttribute(
                Product::ENTITY,
                $attributeId
            );
        } catch (Exception $exception) {
            return $defaultValue;
        }

        $attributeCode = $attribute->getAttributeCode();

        $productValue = $saleableItem->getData($attributeCode);

        try {
            if (! $this->variables->isEmpty($productValue)) {
                if ($attribute->getFrontendInput() !== 'boolean' && $attribute->usesSource()) {
                    $productValue = (string)$attribute->getSource()->getOptionText($productValue);
                }

                if ($attribute->getFrontendInput() === 'price') {
                    $productValue = round(floatval($productValue), 2);

                    $productValue = $this->amountFactory->create($productValue);
                }
            }
        } catch (LocalizedException $exception) {
            return $defaultValue;
        }

        if ($this->variables->isEmpty($productValue)) {
            $productValue = $this->storeHelper->getStoreConfig(
                sprintf(
                    'infrangible_catalogproductprice/%s/%s/default_value',
                    $sectionName,
                    $type
                )
            );
        }

        return $this->variables->isEmpty($productValue) ? $defaultValue : $productValue;
    }
}
