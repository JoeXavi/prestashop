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

function upgrade_module_2_0_10($module)
{
    include_once(_PS_MODULE_DIR_.'quantitydiscountpro/classes/QuantityDiscountRuleFamily.php');

    Db::getInstance()->execute(
        'ALTER TABLE `'._DB_PREFIX_.'quantity_discount_rule_condition`
        ADD `products_default_category` tinyint(1) unsigned DEFAULT NULL,
        ADD `stock_operator` tinyint(1) unsigned DEFAULT NULL AFTER `cart_weight`,
        ADD `stock` int(10) unsigned DEFAULT NULL AFTER `stock_operator`;'
    );

    Db::getInstance()->execute(
        "CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."quantity_discount_rule_condition_order_state` (
          `id_quantity_discount_rule_condition` int(10) unsigned NOT NULL,
          `id_quantity_discount_rule` int(10) unsigned NOT NULL,
          `id_order_state` int(10) unsigned NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;"
    );

    $qdrf = new QuantityDiscountRuleFamily();
    $qdrf->active = 1;
    $qdrf->name = 'Default';
    $qdrf->execute_other_families = 1;
    $qdrf->save();

    Db::getInstance()->execute(
        'UPDATE `'._DB_PREFIX_.'quantity_discount_rule`
        SET `id_family` = 1;'
    );

    return $module;
}
