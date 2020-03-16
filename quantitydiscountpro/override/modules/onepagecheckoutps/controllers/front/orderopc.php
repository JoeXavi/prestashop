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

class onepagecheckoutpsOrderOPCModuleFrontController extends ModuleFrontController
{
    public function processDiscount()
    {
        if (Module::isEnabled('quantitydiscountpro')) {
            include_once(_PS_MODULE_DIR_.'quantitydiscountpro/quantitydiscountpro.php');
            $quantityDiscount = new QuantityDiscountRule();
        }

        if ($this->context->cart->nbProducts() > 0) {
            if (Tools::getValue('action_discount') == 'add' && Tools::getValue('discount_name')) {
                $code = trim(Tools::getValue('discount_name'));
                if (!Validate::isCleanHtml($code)) {
                    $this->errors[] = $this->onepagecheckoutps->l('Voucher name invalid.', $this->name_file);
                } else {
                    if (Module::isEnabled('quantitydiscountpro') && ($quantityDiscount = new quantityDiscountRule(QuantityDiscountRule::getQuantityDiscountRuleByCode($code))) && Validate::isLoadedObject($quantityDiscount)) {
                        if (!$quantityDiscount->createAndRemoveRules($code)) {
                            $this->errors[] = Tools::displayError('You cannot use this voucher');
                        }
                    } elseif (($cartRule = new CartRule(CartRule::getIdByCode($code))) && Validate::isLoadedObject($cartRule)) {
                        if ($error = $cartRule->checkValidity($this->context, false, true)) {
                            $this->errors[] = $error;
                        } else {
                            $this->context->cart->addCartRule($cartRule->id);
                        }
                    } else {
                        $this->errors[] = $this->onepagecheckoutps->l('Voucher name invalid.', $this->name_file);
                    }
                }
            } elseif (Tools::getValue('action_discount') == 'delete' && ($id_cart_rule = (int) Tools::getValue('id_discount')) && Validate::isUnsignedId($id_cart_rule)) {
                if (Module::isEnabled('quantitydiscountpro') && !QuantityDiscountRule::removeQuantityDiscountCartRule($id_cart_rule, (int)$this->context->cart->id)) {
                    $this->context->cart->removeCartRule($id_cart_rule);
                }
                CartRule::autoAddToCart($this->context);
            }
        }

        return array('hasError' => !empty($this->errors), 'errors' => $this->errors);
    }
}
