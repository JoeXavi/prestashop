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

function upgrade_module_2_0_9($module)
{
    Db::getInstance()->execute(
        'ALTER TABLE `'._DB_PREFIX_.'quantity_discount_rule`
        ADD `id_family` int(10) NOT NULL AFTER `description`;'
    );

    Db::getInstance()->execute(
        'ALTER TABLE `'._DB_PREFIX_.'quantity_discount_rule_condition`
        DROP `cart_amount_special`;'
    );

    Db::getInstance()->execute(
        "CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."quantity_discount_rule_family` (
          `id_quantity_discount_rule_family` int(10) unsigned NOT NULL AUTO_INCREMENT,
          `active` tinyint(1) NOT NULL,
          `name` varchar(255) DEFAULT NULL,
          `description` text,
          `priority` int(10) DEFAULT NULL,
          `execute_other_families` tinyint(1) NOT NULL,
          `date_add` datetime DEFAULT NULL,
          `date_upd` datetime DEFAULT NULL,
          PRIMARY KEY (`id_quantity_discount_rule_family`)
        ) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;"
    );

    return $module;
}
