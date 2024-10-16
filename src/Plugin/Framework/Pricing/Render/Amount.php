<?php

declare(strict_types=1);

namespace Infrangible\CatalogProductPrice\Plugin\Framework\Pricing\Render;

use FeWeDev\Base\Variables;
use Infrangible\Core\Helper\Block;

/**
 * @author      Andreas Knollmann
 * @copyright   Copyright (c) 2014-2024 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */
class Amount
{
    /** @var \Infrangible\CatalogProductPrice\Helper\Amount */
    protected $amountHelper;

    /** @var Variables */
    protected $variables;

    /** @var Block */
    protected $blockHelper;

    public function __construct(
        \Infrangible\CatalogProductPrice\Helper\Amount $amountHelper,
        Variables $variables,
        Block $blockHelper
    ) {
        $this->amountHelper = $amountHelper;
        $this->variables = $variables;
        $this->blockHelper = $blockHelper;
    }

    public function beforeToHtml(\Magento\Framework\Pricing\Render\Amount $subject): void
    {
        $cssClasses = $subject->hasData('css_classes') ? explode(
            ' ',
            $subject->getData('css_classes')
        ) : [];

        $cssClasses[] = 'force-display';

        $subject->setData(
            'css_classes',
            join(
                ' ',
                $cssClasses
            )
        );
    }

    public function afterHasAdjustmentsHtml(\Magento\Framework\Pricing\Render\Amount $subject, bool $result): bool
    {
        return $result || ! $this->variables->isEmpty($this->amountHelper->getInformation($subject));
    }

    public function afterGetAdjustmentsHtml(\Magento\Framework\Pricing\Render\Amount $subject, string $result): string
    {
        $information = $this->amountHelper->getInformation($subject);

        if ($this->variables->isEmpty($information)) {
            return $result;
        }

        return $result . $this->blockHelper->renderTemplateBlock(
                $subject,
                'Infrangible_CatalogProductPrice::price/information.phtml',
                [
                    'id' => $subject->getSaleableItem()->getId(),
                    'information' => $information
                ]
            );
    }
}
