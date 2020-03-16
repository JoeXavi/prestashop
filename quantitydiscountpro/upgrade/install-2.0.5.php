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

function upgrade_module_2_0_5($module)
{
    Db::getInstance()->execute(
        'ALTER TABLE `'._DB_PREFIX_.'quantity_discount_rule`
        ADD `compatible_cart_rules` tinyint(1) NOT NULL AFTER `execute_other_rules`;'
    );

    Db::getInstance()->execute(
        "ALTER TABLE `'._DB_PREFIX_.'quantity_discount_rule_action`
        ADD `product_price_operator` tinyint(1) unsigned DEFAULT NULL AFTER `reduction_max_currency`,
        ADD `product_price_amount` decimal(17,2) NOT NULL DEFAULT '0.00' AFTER `product_price_operator`,
        ADD `product_price_currency` int(10) unsigned NOT NULL AFTER `product_price_amount`,
        ADD `product_price_tax` tinyint(1) unsigned NOT NULL AFTER `product_price_currency`;"
    );

    Db::getInstance()->execute(
        "ALTER TABLE `'._DB_PREFIX_.'quantity_discount_rule_condition`
        ADD `product_price_operator` tinyint(1) unsigned DEFAULT NULL AFTER `products_nb_each`,
        ADD `product_price_amount` decimal(17,2) NOT NULL DEFAULT '0.00' AFTER `product_price_operator`,
        ADD `product_price_currency` int(10) unsigned NOT NULL AFTER `product_price_amount`,
        ADD `product_price_tax` tinyint(1) unsigned NOT NULL AFTER `product_price_currency`;"
    );

    return $module;
}
