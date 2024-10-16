<?php

declare(strict_types=1);

namespace Infrangible\CatalogProductPrice\Block;

use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Framework\View\Element\Template;

/**
 * @author      Andreas Knollmann
 * @copyright   Copyright (c) 2014-2024 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */
class Information extends Template
{
    /** @var PriceCurrencyInterface */
    protected $priceCurrency;

    public function __construct(Template\Context $context, PriceCurrencyInterface $priceCurrency, array $data = [])
    {
        parent::__construct(
            $context,
            $data
        );

        $this->priceCurrency = $priceCurrency;
    }

    public function formatCurrency(
        $amount,
        $includeContainer = true,
        $precision = PriceCurrencyInterface::DEFAULT_PRECISION
    ): string {
        return $this->priceCurrency->format(
            $amount,
            $includeContainer,
            $precision
        );
    }
}
