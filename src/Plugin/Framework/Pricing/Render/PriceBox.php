<?php

declare(strict_types=1);

namespace Infrangible\CatalogProductPrice\Plugin\Framework\Pricing\Render;

use Magento\Framework\Pricing\Amount\AmountInterface;

/**
 * @author      Andreas Knollmann
 * @copyright   Copyright (c) 2014-2024 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */
class PriceBox
{
    /** @var \Infrangible\CatalogProductPrice\Helper\PriceBox */
    protected $priceBoxHelper;

    public function __construct(\Infrangible\CatalogProductPrice\Helper\PriceBox $priceBoxHelper)
    {
        $this->priceBoxHelper = $priceBoxHelper;
    }

    public function afterFetchView(\Magento\Framework\Pricing\Render\PriceBox $subject, string $result): string
    {
        return $this->priceBoxHelper->getInformationHtml($subject) . $result;
    }

    public function beforeRenderAmount(
        \Magento\Framework\Pricing\Render\PriceBox $subject,
        AmountInterface $amount,
        array $arguments = []
    ): array {
        $arguments = $this->priceBoxHelper->updateDisplayLabel(
            $subject,
            $arguments
        );

        return [$amount, $arguments];
    }
}
