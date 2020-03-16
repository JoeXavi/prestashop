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

class DiscountController extends DiscountControllerCore
{
    /**
     * Assign template vars related to page content
     * @see FrontController::initContent()
     */
    public function initContent()
    {
        if (Module::isEnabled('quantitydiscountpro')) {
            include_once(_PS_MODULE_DIR_.'quantitydiscountpro/quantitydiscountpro.php');
            include_once(_PS_MODULE_DIR_.'quantitydiscountpro/classes/array_column.php');

            FrontController::initContent();

            $cart_rules = CartRule::getCustomerCartRules($this->context->language->id, $this->context->customer->id, true, false);

            if (is_array(array_filter($cart_rules))) {
                foreach ($cart_rules as $key => &$discount) {
                    if (QuantityDiscountRule::cartRuleGeneratedByAQuantityDiscountRuleCodeWithoutCode($discount['code'])) {
                        unset($cart_rules[$key]);
                        continue;
                    }

                    $discount['value'] = Tools::convertPriceFull(
                        $discount['value'],
                        new Currency((int)$discount['reduction_currency']),
                        new Currency((int)$this->context->cart->id_currency)
                    );
                }
            }

            $nb_cart_rules = count($cart_rules);

            $this->context->smarty->assign(
                array(
                    'nb_cart_rules' => (int)$nb_cart_rules,
                    'cart_rules' => $cart_rules,
                    'discount' => $cart_rules,
                    'nbDiscounts' => (int)$nb_cart_rules
                )
            );

            $this->setTemplate(_PS_THEME_DIR_.'discount.tpl');
        } else {
            parent::initContent();
        }
    }
}
