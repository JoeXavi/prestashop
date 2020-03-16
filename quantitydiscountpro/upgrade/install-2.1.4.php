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

function upgrade_module_2_1_4($module)
{
    Db::getInstance()->execute(
        "ALTER TABLE `"._DB_PREFIX_."quantity_discount_rule_condition`
        ADD `customer_years_from` int(10) NULL AFTER `customer_birthday`,
        ADD `customer_years_to` int(10) NULL AFTER `customer_years_from`,
        ADD `customer_orders_nb_prod_operator` tinyint(1) unsigned DEFAULT NULL AFTER `customer_orders_nb_days`,
        ADD `customer_orders_nb_prod` int(10) unsigned DEFAULT NULL AFTER `customer_orders_nb_prod_operator`;"
    );

    Db::getInstance()->execute(
        "CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."quantity_discount_rule_condition_state` (
            `id_quantity_discount_rule_condition` int(10) unsigned NOT NULL,
            `id_quantity_discount_rule` int(10) unsigned NOT NULL,
            `id_state` int(10) unsigned NOT NULL,
            PRIMARY KEY `primary_ps_quantity_discount_rule_condition_state` (`id_quantity_discount_rule_condition`, `id_quantity_discount_rule`, `id_state`),
            KEY `id_quantity_discount_rule_condition` (`id_quantity_discount_rule_condition`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8"
    );

    $module->registerHook('actionCustomerAccountAdd');

    return true;
}
