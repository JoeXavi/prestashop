<?php
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

class Cart extends CartCore
{
    public function addCartRule($id_cart_rule)
    {
        $result = parent::addCartRule($id_cart_rule);

        if (Module::isEnabled('quantitydiscountpro')) {
            include_once(_PS_MODULE_DIR_.'quantitydiscountpro/quantitydiscountpro.php');
            $quantityDiscountRulesAtCart = QuantityDiscountRule::getQuantityDiscountRulesAtCart((int)Context::getContext()->cart->id);

            if (is_array($quantityDiscountRulesAtCart) && count($quantityDiscountRulesAtCart)) {
                foreach ($quantityDiscountRulesAtCart as $quantityDiscountRuleAtCart) {
                    $quantityDiscountRuleAtCartObj = new QuantityDiscountRule((int)$quantityDiscountRuleAtCart['id_quantity_discount_rule']);
                    if (!$quantityDiscountRuleAtCartObj->compatibleCartRules()) {
                        QuantityDiscountRule::removeQuantityDiscountCartRule($quantityDiscountRuleAtCart['id_cart_rule'], (int)Context::getContext()->cart->id);
                    }
                }
            }
        }

        return $result;
    }

/*
    public function getPackageShippingCost($id_carrier = null, $use_tax = true, Country $default_country = null, $product_list = null, $id_zone = null)
    {
        // Original shipping cost
        $shipping_cost = parent::getPackageShippingCost($id_carrier, $use_tax, $default_country, $product_list, $id_zone);

        if (Module::isEnabled('quantitydiscountpro')) {
            if (!function_exists('array_column')) {
                include_once(_PS_MODULE_DIR_.'quantitydiscountpro/classes/array_column.php');
            }

            if ($this->isVirtualCart()) {
                return 0;
            }

            // Avoid recursion when called from getOrderTotal from module
            $backtrace = version_compare(PHP_VERSION, '5.3.6', '>=') ? debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS) : debug_backtrace();

            if (in_array('QuantityDiscountRule', array_column($backtrace, 'class'))) {
                return $shipping_cost;
            }

            $shippingCostsByCarrier = Tools::jsonDecode(Context::getContext()->cookie->qdp_shipping_cost, true);
            if (isset($shippingCostsByCarrier[$id_carrier])) {
                return max(0, $shipping_cost - $shippingCostsByCarrier[$id_carrier]);
            }
        }

        return $shipping_cost;
    }
*/
}
