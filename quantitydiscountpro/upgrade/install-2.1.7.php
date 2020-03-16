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

function upgrade_module_2_1_7()
{
    Db::getInstance()->execute(
        "CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."quantity_discount_rule_action_feature` (
            `id_quantity_discount_rule_action` int(10) unsigned NOT NULL,
            `id_quantity_discount_rule` int(10) unsigned NOT NULL,
            `id_feature` int(10) unsigned NOT NULL,
            PRIMARY KEY `primary_ps_quantity_discount_rule_action_feature` (`id_quantity_discount_rule_action`, `id_quantity_discount_rule`, `id_feature`),
            KEY `id_quantity_discount_rule_action` (`id_quantity_discount_rule_action`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8"
    );

    Db::getInstance()->execute(
        "ALTER TABLE `"._DB_PREFIX_."quantity_discount_rule_action`
        ADD `filter_by_feature` tinyint(1) unsigned NOT NULL AFTER `filter_by_attribute`;"
    );

    Db::getInstance()->execute(
        "CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."quantity_discount_rule_condition_feature` (
            `id_quantity_discount_rule_condition` int(10) unsigned NOT NULL,
            `id_quantity_discount_rule` int(10) unsigned NOT NULL,
            `id_feature` int(10) unsigned NOT NULL,
            PRIMARY KEY `primary_ps_quantity_discount_rule_condition_feature` (`id_quantity_discount_rule_condition`, `id_quantity_discount_rule`, `id_feature`),
            KEY `id_quantity_discount_rule_condition` (`id_quantity_discount_rule_condition`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8"
    );

    Db::getInstance()->execute(
        "ALTER TABLE `"._DB_PREFIX_."quantity_discount_rule_condition`
        ADD `filter_by_feature` tinyint(1) unsigned NOT NULL AFTER `filter_by_attribute`;"
    );

    Db::getInstance()->execute(
        "ALTER TABLE `"._DB_PREFIX_."quantity_discount_rule`
        ADD `apply_products_already_discounted` tinyint(1) NOT NULL AFTER `compatible_qdp_rules`;"
    );

    Db::getInstance()->execute(
        "UPDATE `"._DB_PREFIX_."quantity_discount_rule`
        SET `apply_products_already_discounted` = 1;"
    );

    return true;
}
