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

            if (Configuration::isCatalogMode()) {
                Tools::redirect('index.php');
            }

            $cart_rules = $this->getTemplateVarCartRules();

            if (is_array(array_filter($cart_rules))) {
                foreach ($cart_rules as $key => &$discount) {
                    if (QuantityDiscountRule::cartRuleGeneratedByAQuantityDiscountRuleCodeWithoutCode($discount['code'])) {
                        unset($cart_rules[$key]);
                    }
                }
            }

            if (count($cart_rules) <= 0) {
                $this->warning[] = $this->trans('You do not have any vouchers.', array(), 'Shop.Notifications.Warning');
            }

            $this->context->smarty->assign(array(
                'cart_rules' => $cart_rules,
            ));

            FrontController::initContent();
            $this->setTemplate('customer/discount');
        } else {
            parent::initContent();
        }
    }
}
