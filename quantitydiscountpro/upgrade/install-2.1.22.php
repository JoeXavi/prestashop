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

function upgrade_module_2_1_22()
{
    //Check if column exists
    $columnExists = Db::getInstance()->getRow(
        "SELECT *
        FROM information_schema.COLUMNS
        WHERE
            TABLE_SCHEMA = '"._DB_NAME_."'
        AND TABLE_NAME = '"._DB_PREFIX_."quantity_discount_rule_action'
        AND COLUMN_NAME = 'spent_amount_from';"
    );

    if (!$columnExists) {
        Db::getInstance()->execute(
            "ALTER TABLE `"._DB_PREFIX_."quantity_discount_rule_action`
                ADD `spent_amount_from` decimal(17,2) NOT NULL DEFAULT '0.00' AFTER `reduction_max_currency`,
                ADD `spent_amount_to` decimal(17,2) NOT NULL DEFAULT '0.00' AFTER `spent_amount_from`,
                ADD `spent_currency` int(10) unsigned NOT NULL AFTER `spent_amount_to`,
                ADD `spent_tax` tinyint(1) NOT NULL AFTER `spent_currency`;"
        );
    }

    return true;
}
