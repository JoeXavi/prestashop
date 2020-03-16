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

function upgrade_module_2_0_1($module)
{
    Db::getInstance()->execute(
        'ALTER TABLE `'._DB_PREFIX_.'quantity_discount_rule_condition`
        ADD `customer_signedup_days` int(10) unsigned NULL AFTER `customer_signedup_date_to`,
        ADD `products_nb_same` tinyint(1) unsigned NULL AFTER `products_nb`,
        ADD `products_nb_same_attributes` tinyint(1) unsigned NULL AFTER `products_nb_same`;'
    );

    Db::getInstance()->execute(
        'ALTER TABLE `'._DB_PREFIX_.'quantity_discount_rule_condition`
            DROP COLUMN cart_nb_operator,
            DROP COLUMN cart_nb,
            DROP COLUMN cart_nb_dif_operator,
            DROP COLUMN cart_nb_dif,
            DROP COLUMN cart_nb_same,
            DROP COLUMN cart_nb_same_attributes,
            DROP COLUMN products_category_operator,
            DROP COLUMN products_category_amount,
            DROP COLUMN products_category_amount_currency,
            DROP COLUMN products_category_amount_tax,
            DROP COLUMN products_category_nb_operator,
            DROP COLUMN products_category_nb,
            DROP COLUMN products_category_nb_dif_operator,
            DROP COLUMN products_category_nb_dif,
            DROP COLUMN attributes_operator,
            DROP COLUMN attributes_amount,
            DROP COLUMN attributes_amount_currency,
            DROP COLUMN attributes_amount_tax,
            DROP COLUMN attributes_nb_operator,
            DROP COLUMN attributes_nb,
            DROP COLUMN attributes_nb_dif_operator,
            DROP COLUMN attributes_nb_dif;'
    );

    return $module;
}
