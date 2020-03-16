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

class ChangeCurrencyController extends ChangeCurrencyControllerCore
{
    public function initContent()
    {
        $currentCurrency = $this->context->currency;
        $currency = new Currency((int)Tools::getValue('id_currency'));
        if (Validate::isLoadedObject($currency) && !$currency->deleted) {
            $this->context->cookie->id_currency = (int)$currency->id;

            if (Module::isEnabled('quantitydiscountpro')) {
                include_once(_PS_MODULE_DIR_.'quantitydiscountpro/quantitydiscountpro.php');

                $this->context->currency = $currency;
                $this->context->cart->id_currency = $currency->id;

                $quantityDiscount = new QuantityDiscountRule();
                $quantityDiscount->createAndRemoveRules();

                $this->context->currency = $currentCurrency;
                $this->context->cart->id_currency = $currentCurrency->id;
            }

            if (version_compare(_PS_VERSION_, '1.6.0.12', '>=')) {
                $this->ajaxDie('1');
            } else {
                die('1');
            }
        }

        if (version_compare(_PS_VERSION_, '1.6.0.12', '>=')) {
            $this->ajaxDie('0');
        } else {
            die('0');
        }
    }
}
