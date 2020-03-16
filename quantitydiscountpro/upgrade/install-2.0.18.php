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

function upgrade_module_2_0_18($module)
{
    $result = true;

    $result &= Db::getInstance()->execute(
        "ALTER TABLE `"._DB_PREFIX_."quantity_discount_rule`
        ADD `code` varchar(10) COLLATE 'utf8_general_ci' NULL AFTER `id_family`,
        DROP `coupon_code`;"
    );

    $result &= Db::getInstance()->execute(
        "ALTER TABLE `"._DB_PREFIX_."quantity_discount_rule_condition`
        ADD `filter_by_product` tinyint(1) NULL,
        ADD `filter_by_attribute` tinyint(1) NULL,
        ADD `filter_by_category` tinyint(1) NULL,
        ADD `filter_by_supplier` tinyint(1) NULL,
        ADD `filter_by_manufacturer` tinyint(1) NULL,
        ADD `filter_by_price` tinyint(1) NULL;"
    );


    $result &= Db::getInstance()->execute(
        'UPDATE `'._DB_PREFIX_.'quantity_discount_rule_condition`
        SET `filter_by_product` = 1,
            `filter_by_attribute` = 1,
            `filter_by_category` = 1,
            `filter_by_supplier` = 1,
            `filter_by_manufacturer` = 1,
            `filter_by_price` = 1;'
    );

    $result &= $module->copyOverrideFolder();
    $result &= $module->uninstallOverrides();
    $result &= $module->installOverrides();

    return true;
}
