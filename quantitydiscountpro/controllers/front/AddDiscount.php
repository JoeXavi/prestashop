<?php
/**
* Card payment REDSYS platform (SERVIRED / SERMEPA)
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

class QuantityDiscountProAddDiscountModuleFrontController extends ModuleFrontController
{
    public function initContent()
    {
        if ($this->context->cart->nbProducts()) {
            if (CartRule::isFeatureActive()) {
                if (!($code = trim(Tools::getValue('discount_name')))) {
                    $this->errors[] = Tools::displayError('You must enter a voucher code.');
                } elseif (!Validate::isCleanHtml($code)) {
                    $this->errors[] = Tools::displayError('The voucher code is invalid.');
                } else {
                    if (($quantityDiscount = new quantityDiscountRule(QuantityDiscountRule::getQuantityDiscountRuleByCode($code))) && Validate::isLoadedObject($quantityDiscount)) {
                        // Add valid rule with discount code
                        if ($quantityDiscount->createAndRemoveRules($code)) {
                            if (Configuration::get('PS_ORDER_PROCESS_TYPE') == 1) {
                                Tools::redirect('index.php?controller=order-opc&addingCartRule=1');
                            }

                            Tools::redirect('index.php?controller=order&addingCartRule=1');
                        } else {
                            $this->errors[] = Tools::displayError('You cannot use this voucher');
                        }
                    } elseif (($cartRule = new CartRule(CartRule::getIdByCode($code))) && Validate::isLoadedObject($cartRule)) {
                        if ($quantityDiscount->cartRuleGeneratedByAQuantityDiscountRuleCode($code)) {
                            //Check if user is trying to manually add a coupon generated automatically
                            $this->errors[] = Tools::displayError('The voucher code is invalid.');
                        } elseif ($error = $cartRule->checkValidity($this->context, false, true)) {
                            $this->errors[] = $error;
                        } else {
                            $this->context->cart->addCartRule($cartRule->id);
                            CartRule::autoAddToCart($this->context);

                            if (Configuration::get('PS_ORDER_PROCESS_TYPE') == 1) {
                                Tools::redirect('index.php?controller=order-opc&addingCartRule=1');
                            }
                            Tools::redirect('index.php?controller=order&addingCartRule=1');
                        }
                    } else {
                        $this->errors[] = Tools::displayError('This voucher does not exists.');
                    }
                }

                $this->context->smarty->assign(array(
                    'errors' => $this->errors,
                    'discount_name' => Tools::safeOutput($code)
                ));
            }
            /* Is there only virtual product in cart */
            if ($this->context->cart->isVirtualCart()) {
                $this->setNoCarrier();
            }
        }

        $this->context->smarty->assign('back', Tools::safeOutput(Tools::getValue('back')));

        Tools::redirect('index.php?controller=order');
    }
}
