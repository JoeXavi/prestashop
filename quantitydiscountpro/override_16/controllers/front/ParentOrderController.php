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

class ParentOrderController extends ParentOrderControllerCore
{
    public function init()
    {
        if (Module::isEnabled('quantitydiscountpro')) {
            include_once(_PS_MODULE_DIR_.'quantitydiscountpro/quantitydiscountpro.php');
            $quantityDiscount = new QuantityDiscountRule();

            $this->isLogged = $this->context->customer->id && Customer::customerIdExistsStatic((int)$this->context->cookie->id_customer);

            FrontController::init();

             /* Disable some cache related bugs on the cart/order */
            header('Cache-Control: no-cache, must-revalidate');
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');

            $this->nbProducts = $this->context->cart->nbProducts();

            if (!$this->context->customer->isLogged(true) && $this->useMobileTheme() && Tools::getValue('step')) {
                Tools::redirect($this->context->link->getPageLink('authentication', true, (int)$this->context->language->id));
            }

            // Redirect to the good order process
            if (Configuration::get('PS_ORDER_PROCESS_TYPE') == 0 && Dispatcher::getInstance()->getController() != 'order') {
                Tools::redirect('index.php?controller=order');
            }

            if (Configuration::get('PS_ORDER_PROCESS_TYPE') == 1 && Dispatcher::getInstance()->getController() != 'orderopc') {
                if (Tools::getIsset('step') && Tools::getValue('step') == 3) {
                    Tools::redirect('index.php?controller=order-opc&isPaymentStep=true');
                }
                Tools::redirect('index.php?controller=order-opc');
            }

            if (Configuration::get('PS_CATALOG_MODE')) {
                $this->errors[] = Tools::displayError('This store has not accepted your new order.');
            }

            if (Tools::isSubmit('submitReorder') && $id_order = (int)Tools::getValue('id_order')) {
                $oldCart = new Cart(Order::getCartIdStatic($id_order, $this->context->customer->id));
                $duplication = $oldCart->duplicate();
                if (!$duplication || !Validate::isLoadedObject($duplication['cart'])) {
                    $this->errors[] = Tools::displayError('Sorry. We cannot renew your order.');
                } else if (!$duplication['success']) {
                    $this->errors[] = Tools::displayError('Some items are no longer available, and we are unable to renew your order.');
                } else {
                    $this->context->cookie->id_cart = $duplication['cart']->id;
                    $context = $this->context;
                    $context->cart = $duplication['cart'];
                    CartRule::autoAddToCart($context);
                    $this->context->cookie->write();

                    $quantityDiscount->createAndRemoveRules();

                    if (Configuration::get('PS_ORDER_PROCESS_TYPE') == 1) {
                        Tools::redirect('index.php?controller=order-opc');
                    }

                    Tools::redirect('index.php?controller=order');
                }
            }

            if ($this->nbProducts) {
                if (CartRule::isFeatureActive()) {
                    if (Tools::isSubmit('submitAddDiscount')) {
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
                    } elseif (($id_cart_rule = (int)Tools::getValue('deleteDiscount')) && Validate::isUnsignedId($id_cart_rule)) {
                        //Remove cart rule. It'll be aded later with CartRule::autoAddToCart() if applicable
                        if (!QuantityDiscountRule::removeQuantityDiscountCartRule($id_cart_rule, (int)$this->context->cart->id)) {
                            $this->context->cart->removeCartRule($id_cart_rule);
                        }
                        CartRule::autoAddToCart($this->context);
                        Tools::redirect('index.php?controller=order-opc');
                    }
                }
                /* Is there only virtual product in cart */
                if ($this->context->cart->isVirtualCart()) {
                    $this->setNoCarrier();
                }
            }

            $this->context->smarty->assign('back', Tools::safeOutput(Tools::getValue('back')));
        } else {
            parent::init();
        }
    }

    public function setMedia()
    {
        parent::setMedia();

        if (Module::isEnabled('quantitydiscountpro') && in_array((int)Tools::getValue('step'), array(0, 2, 3)) || Configuration::get('PS_ORDER_PROCESS_TYPE')) {
            $this->addJS(_MODULE_DIR_.'quantitydiscountpro/views/js/qdp.js', 'all');
        }
    }
}
