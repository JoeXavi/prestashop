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

function upgrade_module_2_0_3($module)
{
    Db::getInstance()->execute(
        'ALTER TABLE `'._DB_PREFIX_.'quantity_discount_rule_condition`
        ADD `products_all_met` tinyint(1) unsigned NULL AFTER `cart_weight`,
        ADD `customer_membership_operator` tinyint(1) unsigned DEFAULT NULL AFTER `customer_first_order`,
        ADD `customer_membership` int(10) unsigned NULL AFTER `customer_membership_operator`,
        ADD `customer_birthday` tinyint(1) unsigned DEFAULT NULL AFTER `customer_membership`,
        ADD `customer_orders_amount_orders` int(10) unsigned DEFAULT NULL AFTER `customer_orders_amount_days`,
        ADD `products_nb_dif_cat_operator` tinyint(1) unsigned DEFAULT NULL AFTER `products_nb_dif`,
        ADD `products_nb_dif_cat` int(10) unsigned NULL AFTER `products_nb_dif_cat_operator`,
        ADD `products_nb_same_category` tinyint(1) unsigned DEFAULT NULL AFTER `products_nb_same_attributes`,
        ADD `products_nb_each` int(10) unsigned DEFAULT NULL AFTER `products_nb_dif_cat`,
        ADD `cart_amount_special` tinyint(1) unsigned NULL AFTER `cart_amount_shipping`;'
    );

    Db::getInstance()->execute(
        "ALTER TABLE `"._DB_PREFIX_."quantity_discount_rule_action`
        ADD `reduction_max_amount` decimal(17,2) NOT NULL DEFAULT '0.00' AFTER `reduction_shipping`,
        ADD `reduction_max_currency` int(10) unsigned NOT NULL AFTER `reduction_max_amount`,
        ADD `apply_discount_to_nb` int(10) unsigned NOT NULL AFTER `apply_discount_to`,
        ADD `apply_discount_sort` enum('cheapest','expensive') COLLATE 'utf8_general_ci' NOT NULL AFTER `apply_discount_to`,
        CHANGE `apply_discount_to` `apply_discount_to` enum('order','product','all') COLLATE 'utf8_general_ci' NOT NULL AFTER `reduction_max_currency`;
        DROP COLUMN `nb_free_products`;"
    );

    return $module;
}
