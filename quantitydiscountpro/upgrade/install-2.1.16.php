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

function upgrade_module_2_1_16()
{
    Db::getInstance()->execute(
        "CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."quantity_discount_rule_cart_product` (
            `id_cart` int(10) unsigned NOT NULL,
            `id_quantity_discount_rule` int(10) unsigned NOT NULL,
            `id_product` int(10) unsigned NOT NULL,
            `id_product_attribute` int(10) unsigned DEFAULT NULL,
            `quantity` int(10) unsigned DEFAULT NULL,
            KEY `id_cart_id_quantity_discount_rule` (`id_cart`,`id_quantity_discount_rule`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8"
    );

    //Check if table exists
    $columnExists = Db::getInstance()->getRow(
        "SELECT *
        FROM information_schema.COLUMNS
        WHERE
            TABLE_SCHEMA = '"._DB_NAME_."'
        AND TABLE_NAME = '"._DB_PREFIX_."quantity_discount_rule_cart'
        AND COLUMN_NAME = 'id_action_type';"
    );

    if (!$columnExists) {
        Db::getInstance()->execute(
            "ALTER TABLE `"._DB_PREFIX_."quantity_discount_rule_cart`
            ADD `id_action_type` int(10) unsigned NULL;"
        );
    }

    return true;
}
