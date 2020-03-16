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

function upgrade_module_2_1_10($module)
{
    Db::getInstance()->execute(
        "CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."quantity_discount_rule_action_carrier` (
            `id_quantity_discount_rule_action` int(10) unsigned NOT NULL,
            `id_quantity_discount_rule` int(10) unsigned NOT NULL,
            `id_carrier` int(10) unsigned NOT NULL,
            PRIMARY KEY `primary_ps_quantity_discount_rule_action_carrier` (`id_quantity_discount_rule_action`, `id_quantity_discount_rule`, `id_carrier`),
            KEY `id_quantity_discount_rule_action` (`id_quantity_discount_rule_action`)
        ) ENGINE="._MYSQL_ENGINE_." DEFAULT CHARSET=utf8;"
    );

    //Check if table exists
    $columnExists = Db::getInstance()->getRow(
        "SELECT *
        FROM information_schema.COLUMNS
        WHERE
            TABLE_SCHEMA = '"._DB_NAME_."'
        AND TABLE_NAME = '"._DB_PREFIX_."quantity_discount_rule_action'
        AND COLUMN_NAME = 'filter_by_carrier';"
    );

    if (!$columnExists) {
        Db::getInstance()->execute(
            "ALTER TABLE `"._DB_PREFIX_."quantity_discount_rule_action`
            ADD `filter_by_carrier` tinyint(1) unsigned NOT NULL;"
        );
    }

    $module->copyOverrideFolder();
    $module->removeOverride('Cart');
    $module->addOverride('Cart');

    return true;
}
