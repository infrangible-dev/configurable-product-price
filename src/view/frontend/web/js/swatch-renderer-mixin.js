/**
 * @author      Andreas Knollmann
 * @copyright   Copyright (c) 2014-2024 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */

define([
    'jquery',
    'priceUtils',
    'mage/template'
], function ($, priceUtils, template) {
    'use strict';

    var swatchRendererMixin = {
        catalogProductPriceOptions: {
            priceBoxSelection: '.product-info-main [data-role=priceBox]',
            finalPriceSelection: '.product-info-main .normal-price',
            finalPriceDisplayLabelSelection: '.product-info-main .normal-price .price-label',
            finalPricePriceSelection: '.product-info-main .normal-price .price-wrapper',
            finalPriceInformationSelection: '.product-info-main .normal-price .price-information',
            oldPriceDisplayLabelSelection: '.product-info-main .old-price .price-label',
            oldPricePriceSelection: '.product-info-main .old-price .price-wrapper',
            oldPriceInformationSelection: '.product-info-main .old-price .price-information',
            informationSelection: '.product-info-main [data-role=priceBox] > .price-information',
            informationDisplayLabelSelection: '.product-info-main .price-information .price-label',
            informationPriceSelection: '.product-info-main .price-information .price-wrapper',
            informationInformationSelection: '.product-info-main .price-information .price-information'
        },

        _UpdatePrice: function () {
            this._super();

            var prices = this._getNewPrices();

            $(this.catalogProductPriceOptions.priceBoxSelection).find('span:first').removeClass('special-price');

            var finalPriceDisplayLabelNode = $(this.catalogProductPriceOptions.finalPriceDisplayLabelSelection);

            var finalPriceDisplayLabel = prices.finalPrice.displayLabel;
            if (finalPriceDisplayLabel) {
                finalPriceDisplayLabelNode.html(finalPriceDisplayLabel);
                finalPriceDisplayLabelNode.show();
            } else {
                finalPriceDisplayLabelNode.html('');
                finalPriceDisplayLabelNode.hide();

                var isShow = typeof prices !== 'undefined' && prices.oldPrice.amount !== prices.finalPrice.amount;
                $(this.catalogProductPriceOptions.finalPriceSelection).toggleClass('special-price', isShow);
            }

            var finalPriceInformationNode = $(this.catalogProductPriceOptions.finalPriceInformationSelection);
            if (finalPriceInformationNode.length === 0) {
                finalPriceInformationNode = $('<span>', {class: 'price-information'});
                $(this.catalogProductPriceOptions.finalPricePriceSelection).after(finalPriceInformationNode);
            }

            var finalPriceInformation = prices.finalPrice.information;
            if (finalPriceInformation) {
                finalPriceInformationNode.html(finalPriceInformation);
                finalPriceInformationNode.show();
            } else {
                finalPriceInformationNode.html('');
                finalPriceInformationNode.hide();
            }

            var oldPriceDisplayLabelNode = $(this.catalogProductPriceOptions.oldPriceDisplayLabelSelection);

            var oldPriceDisplayLabel = prices.oldPrice.displayLabel;
            if (oldPriceDisplayLabel) {
                oldPriceDisplayLabelNode.html(oldPriceDisplayLabel);
                oldPriceDisplayLabelNode.show();
            } else {
                oldPriceDisplayLabelNode.html('');
                oldPriceDisplayLabelNode.hide();
            }

            var oldPriceInformationNode = $(this.catalogProductPriceOptions.oldPriceInformationSelection);

            var oldPriceInformation = prices.oldPrice.information;
            if (oldPriceInformation) {
                if (oldPriceInformationNode.length === 0) {
                    oldPriceInformationNode = $('<span>', {class: 'price-information'});
                    $(this.catalogProductPriceOptions.oldPricePriceSelection).after(oldPriceInformationNode);
                }

                oldPriceInformationNode.html(oldPriceInformation);
                oldPriceInformationNode.show();
            } else {
                if (oldPriceInformationNode.length > 0) {
                    oldPriceInformationNode.remove();
                }
            }

            var informationNode = $(this.catalogProductPriceOptions.informationSelection);

            var priceInformation = prices.priceInformation;
            if (priceInformation) {
                if (informationNode.length === 0) {
                    informationNode = $('<span>', {class: 'price-information'});
                    $(this.catalogProductPriceOptions.finalPriceSelection).before(informationNode);
                }

                var templateData = {};

                templateData.displayLabel = priceInformation.displayLabel;

                var informationAmountValue = priceInformation.price;
                templateData.amountValue = informationAmountValue;
                templateData.amount = informationAmountValue ?
                    priceUtils.formatPrice(informationAmountValue, this.options.jsonConfig.currencyFormat) : null;

                templateData.information = priceInformation.information;

                informationNode.html(template($('#catalog-product-price-information-template').html(), templateData));
            } else {
                if (informationNode.length > 0) {
                    informationNode.remove();
                }
            }
        }
    };

    return function (targetWidget) {
        $.widget('mage.SwatchRenderer', targetWidget, swatchRendererMixin);

        return $.mage.SwatchRenderer;
    };
});
