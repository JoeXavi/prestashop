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

class CartController extends CartControllerCore
{
    protected function updateCart()
    {
        if (Module::isEnabled('quantitydiscountpro')) {
            include_once(_PS_MODULE_DIR_.'quantitydiscountpro/quantitydiscountpro.php');
            // Update the cart ONLY if $this->cookies are available, in order to avoid ghost carts created by bots
            if ($this->context->cookie->exists() && !$this->errors && !($this->context->customer->isLogged() && !$this->isTokenValid())) {
                if (Tools::getIsset('add') || Tools::getIsset('update')) {
                    $this->processChangeProductInCart();
                } elseif (Tools::getIsset('delete')) {
                    $this->processDeleteProductInCart();
                } elseif (CartRule::isFeatureActive()) {
                    if (Tools::getIsset('addDiscount')) {
                        if (!($code = trim(Tools::getValue('discount_name')))) {
                            $this->errors[] = $this->trans('You must enter a voucher code.', array(), 'Shop.Notifications.Error');
                        } elseif (!Validate::isCleanHtml($code)) {
                            $this->errors[] = $this->trans('The voucher code is invalid.', array(), 'Shop.Notifications.Error');
                        } else {
                            $quantityDiscount = new QuantityDiscountRule();
                            if (($quantityDiscount = new quantityDiscountRule(QuantityDiscountRule::getQuantityDiscountRuleByCode($code))) && Validate::isLoadedObject($quantityDiscount)) {
                                // Add valid rule with discount code
                                if ($quantityDiscount->createAndRemoveRules($code) !== true) {
                                    $this->errors[] = $this->trans('The voucher code is invalid.', array(), 'Shop.Notifications.Error');
                                }
                            } elseif (($cartRule = new CartRule(CartRule::getIdByCode($code))) && Validate::isLoadedObject($cartRule)) {
                                //Check if user is trying to manually add a coupon generated automatically
                                if ($quantityDiscount->cartRuleGeneratedByAQuantityDiscountRuleCode($code)) {
                                    $this->errors[] = $this->trans('The voucher code is invalid.', array(), 'Shop.Notifications.Error');
                                } elseif ($error = $cartRule->checkValidity($this->context, false, true)) {
                                    $this->errors[] = $error;
                                } else {
                                    $this->context->cart->addCartRule($cartRule->id);
                                }
                            } else {
                                $this->errors[] = $this->trans('The voucher does not exists.', array(), 'Shop.Notifications.Error');
                            }
                        }
                    } elseif (($id_cart_rule = (int)Tools::getValue('deleteDiscount')) && Validate::isUnsignedId($id_cart_rule)) {
                        //Remove cart rule. It'll be aded later with CartRule::autoAddToCart() if applicable
                        if (!QuantityDiscountRule::removeQuantityDiscountCartRule($id_cart_rule, (int)$this->context->cart->id)) {
                            $this->context->cart->removeCartRule($id_cart_rule);
                        }
                        CartRule::autoAddToCart($this->context);
                    }
                }
            } elseif (!$this->isTokenValid() && Tools::getValue('action') !== 'show' && !Tools::getValue('ajax')) {
                Tools::redirect('index.php');
            }
        } else {
            parent::updateCart();
        }
    }
}
