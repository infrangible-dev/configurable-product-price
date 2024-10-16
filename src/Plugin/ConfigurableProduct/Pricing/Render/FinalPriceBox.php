<?php

declare(strict_types=1);

namespace Infrangible\CatalogProductPrice\Plugin\ConfigurableProduct\Pricing\Render;

use Infrangible\CatalogProductPrice\Helper\PriceBox;
use Magento\Framework\Pricing\Amount\AmountInterface;

/**
 * @author      Andreas Knollmann
 * @copyright   Copyright (c) 2014-2024 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */
class FinalPriceBox
{
    /** @var PriceBox */
    protected $priceBoxHelper;

    public function __construct(PriceBox $priceBoxHelper)
    {
        $this->priceBoxHelper = $priceBoxHelper;
    }

    public function afterFetchView(
        \Magento\ConfigurableProduct\Pricing\Render\FinalPriceBox $subject,
        string $result
    ): string {
        return $this->priceBoxHelper->getInformationHtml($subject) . $result;
    }

    public function beforeRenderAmount(
        \Magento\ConfigurableProduct\Pricing\Render\FinalPriceBox $subject,
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
