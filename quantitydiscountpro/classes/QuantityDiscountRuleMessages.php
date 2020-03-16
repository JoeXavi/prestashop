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

include_once(_PS_MODULE_DIR_.'quantitydiscountpro/classes/array_column.php');

class QuantityDiscountRuleMessages extends ObjectModel
{
    public $id_quantity_discount_rule;
    public $hookDisplayLeftColumnProduct;
    public $hookDisplayRightColumnProduct;
    public $hookDisplayLeftColumn;
    public $hookDisplayRightColumn;
    public $hookDisplayProductButtons;
    public $hookDisplayProductTab;
    public $hookDisplayProductTabContent;
    public $hookDisplayFooterProduct;
    public $hookDisplayTop;
    public $hookShoppingCart;
    public $hookShoppingCartExtra;
    public $hookDisplayFooter;

    public static $definition = array(
        'table' => 'quantity_discount_rule_messages',
        'primary' => 'id_quantity_discount_rule',
        'multilang' => false,
        'fields' => array(
            'id_quantity_discount_rule' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true),
            'hookDisplayLeftColumnProduct'      => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'hookDisplayRightColumnProduct'     => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'hookDisplayLeftColumn'             => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'hookDisplayRightColumn'            => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'hookDisplayProductButtons'         => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'hookDisplayProductTab'             => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'hookDisplayProductTabContent'      => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'hookDisplayFooterProduct'          => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'hookDisplayTop'                    => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'hookShoppingCart'                  => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'hookShoppingCartExtra'             => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'hookDisplayFooter'                 => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
        ),
    );

    public static function getMessagesType()
    {
        return array(
            'Product page hooks' => array(
                array(
                    'id' => 1,
                    'name' => 'hookDisplayLeftColumnProduct'
                ),
                array(
                    'id' => 2,
                    'name' => 'hookDisplayRightColumnProduct'
                ),
                array(
                    'id' => 3,
                    'name' => 'hookDisplayProductButtons'
                ),
                array(
                    'id' => 4,
                    'name' => 'hookDisplayProductTab'
                ),
                array(
                    'id' => 5,
                    'name' => 'hookDisplayProductTabContent'
                ),
                array(
                    'id' => 6,
                    'name' => 'hookDisplayFooterProduct'
                ),
            ),
            'General hooks' => array(
                array(
                    'id' => 10,
                    'name' => 'hookDisplayLeftColumn'
                ),
                array(
                    'id' => 11,
                    'name' => 'hookDisplayRightColumn'
                ),
                array(
                    'id' => 12,
                    'name' => 'hookDisplayTop'
                ),
                array(
                    'id' => 13,
                    'name' => 'hookDisplayFooter'
                ),
            ),
            'Shopping Cart' => array(
                array(
                    'id' => 20,
                    'name' => 'hookShoppingCart'
                ),
                array(
                    'id' => 21,
                    'name' => 'hookShoppingCartExtra'
                ),
            ),
        );
    }

    public static function replaceMessage($message, $quantityDiscountRule)
    {
        $groupConditions = $quantityDiscountRule->getGroups(true);
        foreach ($groupConditions as $groupCondition) {
            $conditions = $groupCondition->getConditions();
            $context = Context::getContext();
            foreach ($conditions as $condition) {
                $condition = new QuantityDiscountRuleCondition($condition['id_quantity_discount_rule_condition']);
                switch ((int)$condition->id_type) {
                    //Number of products in cart
                    case 7:
                        $cartProducts = $context->cart->getProducts();
                        $cartNb = 0;
                        if (!$condition->cart_nb_same && !$condition->cart_nb_same_attributes) {
                            $cartNb = Cart::getNbProducts($context->cart->id);
                        } elseif ($condition->cart_nb_same && !$condition->cart_nb_same_attributes) {
                            $productsResume = array();
                            foreach ($cartProducts as $cartProduct) {
                                $productsResume[$cartProduct['id_product']] = 0;
                            }

                            foreach ($cartProducts as $cartProduct) {
                                $productsResume[$cartProduct['id_product']] += $cartProduct['cart_quantity'];
                            }

                            foreach ($productsResume as $productResume) {
                                $cartNb = max($cartNb, $productResume);
                            }
                        } elseif (!$condition->cart_nb_same && $condition->cart_nb_same_attributes) {
                            $productsResume = array();

                            foreach ($cartProducts as $cartProduct) {
                                $productsResume[$cartProduct['id_product_attribute']] = 0;
                            }

                            foreach ($cartProducts as $cartProduct) {
                                $productsResume[$cartProduct['id_product_attribute']] += $cartProduct['cart_quantity'];
                            }

                            foreach ($productsResume as $productResume) {
                                $cartNb = max($cartNb, $productResume);
                            }
                        } elseif ($condition->cart_nb_same && $condition->cart_nb_same_attributes) {
                            $cartProducts = $context->cart->getProducts();
                            foreach ($cartProducts as $cartProduct) {
                                $cartNb = max($cartNb, (int)$cartProduct['cart_quantity']);
                            }
                        }

                        $from = array(
                            '{nb_products_rem}',
                            '{nb_products_different_rem}'
                        );
                        $to = array(
                            (int)$condition->cart_nb-(int)$cartNb,
                            (int)$condition->cart_nb-(int)$cartNb
                        );
                        $message = str_replace($from, $to, $message);
                        break;
                    //Cart amount
                    case 8:
                        $cartAmount = $condition->cart_amount;
                        if ((int)$condition->cart_amount_currency != $context->currency->id) {
                            $cartAmount = Tools::convertPriceFull($cartAmount, new Currency((int)$condition->cart_amount_currency), $context->currency);
                        }

                        $cartTotal = $context->cart->getOrderTotal((int)$condition->cart_amount_tax, Cart::ONLY_PRODUCTS);
                        if ($condition->cart_amount_shipping) {
                            $cartTotal += $context->cart->getOrderTotal($condition->cart_amount_tax, Cart::ONLY_SHIPPING);
                        }
                        $products = $context->cart->getProducts();
                        $cartRules = $context->cart->getCartRules();

                        foreach ($cartRules as &$cartRule) {
                            if ($cartRule['gift_product']) {
                                foreach ($products as &$product) {
                                    if (empty($product['gift']) && $product['id_product'] == $cartRule['gift_product'] && $product['id_product_attribute'] == $cartRule['gift_product_attribute']) {
                                        $cartTotal = Tools::ps_round($cartTotal - $product[$condition->cart_amount_tax ? 'price_wt' : 'price'], (int)$context->currency->decimals * _PS_PRICE_DISPLAY_PRECISION_);
                                    }
                                }
                            }
                        }

                        $message = str_replace('{cart_amount}', Tools::displayPrice($cartAmount-$cartTotal), $message);
                        break;
                    //Cart weight
                    case 9:
                        $cartWeight = $context->cart->getTotalWeight();
                        $message = str_replace('{cart_weight}', $condition->cart_weight-$cartWeight.' '.Configuration::get('PS_WEIGHT_UNIT'), $message);
                        break;
                    //Product selection
                    case 10:
                        $cartProducts = $context->cart->getProducts();

                        $count_matching_products = 0;
                        $quantity_matching_products = 0;
                        $productsResume = array();
                        $conditionProducts = $condition->getAssociatedRestrictions('product', true, true);
                        foreach ($cartProducts as $cartProduct) {
                            if (in_array($cartProduct['id_product'], array_column($conditionProducts['selected'], 'id_product'))) {
                                $count_matching_products++;
                                $quantity_matching_products += $cartProduct['cart_quantity'];
                                $productsResume[$cartProduct['id_product']] = true;
                            }
                        }

                        $from = array(
                            '{nb_selected_products_rem}',
                            '{nb_selected_products_different_rem}',
                            '{list_selected_products}'
                        );

                        $to = array(
                            (int)$condition->products_nb-(int)$quantity_matching_products,
                            (int)$condition->products_nb_dif-(int)count($productsResume),
                            implode(", ", array_column($conditionProducts['selected'], 'name')),
                        );

                        $message = str_replace($from, $to, $message);
                        break;
                    //Category selection
                    case 19:
                        $cartProducts = $context->cart->getProducts();

                        $count_matching_products = 0;
                        $quantity_matching_products = 0;
                        $conditionProducts = $condition->getAssociatedRestrictions('category', true, true);
                        foreach ($cartProducts as $cartProduct) {
                            $productCategories = Product::getProductCategories($cartProduct['id_product']);
                            foreach ($productCategories as $productCategory) {
                                if (in_array($productCategory, array_column($conditionProducts['selected'], 'id_category'))) {
                                    $count_matching_products++;
                                    $quantity_matching_products += $cartProduct['cart_quantity'];
                                    break;
                                }
                            }
                        }

                        $from = array(
                            '{nb_selected_products_rem}',
                            '{nb_selected_products_different_rem}',
                            '{list_selected_products}'
                        );

                        $to = array(
                            (int)$condition->products_category_nb-(int)$quantity_matching_products,
                            (int)$condition->products_category_nb_dif-(int)count($count_matching_products),
                            implode(", ", array_column($conditionProducts['selected'], 'name')),
                        );

                        break;
                    //Products attributes
                    case 20:
                        $cartProducts = $context->cart->getProducts();

                        $count_matching_products = 0;
                        $quantity_matching_products = 0;
                        $productsResume = array();
                        $conditionProducts = $condition->getAssociatedRestrictions('attribute', false, true);
                        foreach ($cartProducts as $cartProduct) {
                            $product = new Product((int)$cartProduct['id_product']);
                            $combinations = $product->getAttributeCombinationsById((int)$cartProduct['id_product_attribute'], $context->cookie->id_language);
                            foreach ($combinations as $combination) {
                                if (in_array($combination['id_attribute'], array_column($conditionProducts['selected'], 'id_attribute'))) {
                                    $count_matching_products++;
                                    $quantity_matching_products += $cartProduct['cart_quantity'];
                                    $productsResume[$cartProduct['id_product']] = true;
                                }
                            }
                        }

                        $from = array(
                            '{nb_selected_attributes_rem}',
                            '{nb_selected_attributes_different_rem}',
                            '{list_selected_attributes}'
                        );

                        $to = array(
                            (int)$condition->attributes_nb-(int)$quantity_matching_products,
                            (int)$condition->attributes_nb_dif-(int)count($productsResume),
                            implode(", ", array_column($conditionProducts['selected'], 'name')),
                        );

                        $message = str_replace($from, $to, $message);

                        break;
                }
            }
        }

        return $message;
    }
}
