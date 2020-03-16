/**
* Quantity Discount Pro
*
* NOTICE OF LICENSE
*
* This product is licensed for one customer to use on one installation (test stores and multishop included).
* Site developer has the right to modify this module to suit their needs, but can not redistribute the module in
* whole or in part. Any other use of this module constitues a violation of the user agreement.
*
* DISCLAIMER
*
* NO WARRANTIES OF DATA SAFETY OR MODULE SECURITY
* ARE EXPRESSED OR IMPLIED. USE THIS MODULE IN ACCORDANCE
* WITH YOUR MERCHANT AGREEMENT, KNOWING THAT VIOLATIONS OF
* PCI COMPLIANCY OR A DATA BREACH CAN COST THOUSANDS OF DOLLARS
* IN FINES AND DAMAGE A STORES REPUTATION. USE AT YOUR OWN RISK.
*
*  @author    idnovate.com <info@idnovate.com>
*  @copyright 2019 idnovate.com
*  @license   See above
*/

if (typeof updateCartSummary === "function") {
    updateCartSummary = (function() {
        var updateCartSummaryCached = updateCartSummary;

        return function(json) {
            updateCartSummaryCached.apply(this, arguments);

            if (json && !$.isEmptyObject(json.discounts)) {
                for (i=0; i<json.discounts.length; i++) {
                    if ($('#cart_discount_' + json.discounts[i].id_cart_rule).length == 0) {
                        if (priceDisplayMethod !== 0)
                            var discountedPrice = formatCurrency(json.discounts[i].value_tax_exc * -1, currencyFormat, currencySign, currencyBlank);
                        else
                            var discountedPrice = formatCurrency(json.discounts[i].value_real * -1, currencyFormat, currencySign, currencyBlank);

                        $('#cart_summary').append("<tbody><tr class='cart_discount last_item' id='cart_discount_"+json.discounts[i].id_cart_rule+"'><td class='cart_discount_name' colspan='3'>"+json.discounts[i].name+"</td><td class='cart_discount_price'><span class='price-discount'>"+discountedPrice+"</span></td><td class='cart_discount_delete'>1</td><td class='cart_discount_price'><span class='price-discount price'>"+discountedPrice+"</span></td><td class='price_discount_del'></td></tr></tbody>");
                    }
                }
            }

            if (json && $.isEmptyObject(json.gift_products)) {
                $('#cart_summary [id ^=product_][id $=gift]').remove();
            }
        };
    })();
};