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

function upgrade_module_2_0_11($module)
{
    Db::getInstance()->execute(
        "ALTER TABLE `"._DB_PREFIX_."quantity_discount_rule_condition_attribute`
        DROP INDEX `id_attribute`,
        ADD PRIMARY KEY `primary_ps_quantity_discount_rule_condition_attribute` (`id_quantity_discount_rule_condition`, `id_quantity_discount_rule`, `id_attribute`),
        ADD INDEX `id_quantity_discount_rule_condition` (`id_quantity_discount_rule_condition`);"
    );

    Db::getInstance()->execute(
        "ALTER TABLE `"._DB_PREFIX_."quantity_discount_rule_condition_carrier`
        ADD PRIMARY KEY `primary_ps_quantity_discount_rule_condition_carrier` (`id_quantity_discount_rule_condition`, `id_quantity_discount_rule`, `id_carrier`),
        ADD INDEX `id_quantity_discount_rule_condition` (`id_quantity_discount_rule_condition`);"
    );

    Db::getInstance()->execute(
        "ALTER TABLE `"._DB_PREFIX_."quantity_discount_rule_condition_category`
        ADD PRIMARY KEY `primary_ps_quantity_discount_rule_condition_category` (`id_quantity_discount_rule_condition`, `id_quantity_discount_rule`, `id_category`),
        ADD INDEX `id_quantity_discount_rule_condition` (`id_quantity_discount_rule_condition`),
        DROP INDEX `id_category`;"
    );

    Db::getInstance()->execute(
        "ALTER TABLE `"._DB_PREFIX_."quantity_discount_rule_condition_country`
        ADD PRIMARY KEY `primary_ps_quantity_discount_rule_condition_country` (`id_quantity_discount_rule_condition`, `id_quantity_discount_rule`, `id_country`),
        ADD INDEX `id_quantity_discount_rule_condition` (`id_quantity_discount_rule_condition`);"
    );

    Db::getInstance()->execute(
        "ALTER TABLE `"._DB_PREFIX_."quantity_discount_rule_condition_group`
        ADD PRIMARY KEY `primary_ps_quantity_discount_rule_condition_group` (`id_quantity_discount_rule_condition`, `id_quantity_discount_rule`, `id_group`),
        ADD INDEX `id_quantity_discount_rule_condition` (`id_quantity_discount_rule_condition`);"
    );

    Db::getInstance()->execute(
        "ALTER TABLE `"._DB_PREFIX_."quantity_discount_rule_condition_manufacturer`
        ADD PRIMARY KEY `primary_ps_quantity_discount_rule_condition_manufacturer` (`id_quantity_discount_rule_condition`, `id_quantity_discount_rule`, `id_manufacturer`),
        ADD INDEX `id_quantity_discount_rule_condition` (`id_quantity_discount_rule_condition`);"
    );

    Db::getInstance()->execute(
        "ALTER TABLE `"._DB_PREFIX_."quantity_discount_rule_condition_order_state`
        ADD PRIMARY KEY `primary_ps_quantity_discount_rule_condition_order_state` (`id_quantity_discount_rule_condition`, `id_quantity_discount_rule`, `id_order_state`),
        ADD INDEX `id_quantity_discount_rule_condition` (`id_quantity_discount_rule_condition`);"
    );

    Db::getInstance()->execute(
        "ALTER TABLE `"._DB_PREFIX_."quantity_discount_rule_condition_product`
        ADD PRIMARY KEY `primary_ps_quantity_discount_rule_condition_product` (`id_quantity_discount_rule_condition`, `id_quantity_discount_rule`, `id_product`),
        ADD INDEX `id_quantity_discount_rule_condition` (`id_quantity_discount_rule_condition`);"
    );

    Db::getInstance()->execute(
        "ALTER TABLE `"._DB_PREFIX_."quantity_discount_rule_condition_shop`
        ADD PRIMARY KEY `primary_ps_quantity_discount_rule_condition_shop` (`id_quantity_discount_rule_condition`, `id_quantity_discount_rule`, `id_shop`),
        ADD INDEX `id_quantity_discount_rule_condition` (`id_quantity_discount_rule_condition`);"
    );

    Db::getInstance()->execute(
        "ALTER TABLE `"._DB_PREFIX_."quantity_discount_rule_condition_supplier`
        ADD PRIMARY KEY `primary_ps_quantity_discount_rule_condition_supplier` (`id_quantity_discount_rule_condition`, `id_quantity_discount_rule`, `id_supplier`),
        ADD INDEX `id_quantity_discount_rule_condition` (`id_quantity_discount_rule_condition`);"
    );

    Db::getInstance()->execute(
        "ALTER TABLE `"._DB_PREFIX_."quantity_discount_rule_condition_zone`
        ADD PRIMARY KEY `primary_ps_quantity_discount_rule_condition_zone` (`id_quantity_discount_rule_condition`, `id_quantity_discount_rule`, `id_zone`),
        ADD INDEX `id_quantity_discount_rule_condition` (`id_quantity_discount_rule_condition`);"
    );

    Db::getInstance()->execute(
        "ALTER TABLE `"._DB_PREFIX_."quantity_discount_rule_action_attribute`
        ADD PRIMARY KEY `primary_ps_quantity_discount_rule_action_attribute` (`id_quantity_discount_rule_action`, `id_quantity_discount_rule`, `id_attribute`),
        ADD INDEX `id_quantity_discount_rule_action` (`id_quantity_discount_rule_action`),
        DROP INDEX `id_attribute`;"
    );

    Db::getInstance()->execute(
        "ALTER TABLE `"._DB_PREFIX_."quantity_discount_rule_action_category`
        ADD PRIMARY KEY `primary_ps_quantity_discount_rule_action_category` (`id_quantity_discount_rule_action`, `id_quantity_discount_rule`, `id_category`);"
    );

    Db::getInstance()->execute(
        "ALTER TABLE `"._DB_PREFIX_."quantity_discount_rule_action_product`
        ADD PRIMARY KEY `primary_ps_quantity_discount_rule_action_product` (`id_quantity_discount_rule_action`, `id_quantity_discount_rule`, `id_product`);"
    );

    return $module;
}
