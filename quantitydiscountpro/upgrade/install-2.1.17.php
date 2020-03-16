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

function upgrade_module_2_1_17()
{
    //Check if table exists
    $columnExists = Db::getInstance()->getRow(
        "SELECT *
        FROM information_schema.COLUMNS
        WHERE
            TABLE_SCHEMA = '"._DB_NAME_."'
        AND TABLE_NAME = '"._DB_PREFIX_."quantity_discount_rule'
        AND COLUMN_NAME = 'times_used';"
    );

    if ($columnExists) {
        Db::getInstance()->execute(
            "ALTER TABLE `"._DB_PREFIX_."quantity_discount_rule`
            DROP `times_used`;"
        );
    }

    //Convert id_carrier to id_reference
    $rows = Db::getInstance()->executeS(
        "SELECT *
        FROM `"._DB_PREFIX_."quantity_discount_rule_condition_carrier`;"
    );

    foreach ($rows as $row) {
        $carrier = new Carrier((int)$row['id_carrier']);
        if (Validate::isLoadedObject($carrier)) {
            Db::getInstance()->execute(
                "UPDATE `"._DB_PREFIX_."quantity_discount_rule_condition_carrier`
                SET  `id_carrier` = ".(int)$carrier->id_reference."
                WHERE `id_quantity_discount_rule_condition` = ".(int)$row['id_quantity_discount_rule_condition']."
                    AND `id_quantity_discount_rule` = ".(int)$row['id_quantity_discount_rule'].";"
            );
        }
    }

    $columnExists = Db::getInstance()->getRow(
        "SELECT *
        FROM information_schema.COLUMNS
        WHERE
            TABLE_SCHEMA = '"._DB_NAME_."'
        AND TABLE_NAME = '"._DB_PREFIX_."quantity_discount_rule_action'
        AND COLUMN_NAME = 'product_price_to_tax';"
    );

    if (!$columnExists) {
        $result = Db::getInstance()->execute(
            "ALTER TABLE `"._DB_PREFIX_."quantity_discount_rule_action`
            ADD `product_price_from` decimal(17,2) NOT NULL DEFAULT '0.00' AFTER `product_price_operator`,
            ADD `product_price_from_currency` int(10) unsigned NOT NULL AFTER `product_price_from`,
            ADD `product_price_from_tax` tinyint(1) NOT NULL AFTER `product_price_from_currency`,
            ADD `product_price_to` decimal(17,2) NOT NULL DEFAULT '0.00' AFTER `product_price_from_tax`,
            ADD `product_price_to_currency` int(10) unsigned NOT NULL AFTER `product_price_to`,
            ADD `product_price_to_tax` tinyint(1) NOT NULL AFTER `product_price_to_currency`;"
        );
    }

    $actions = Db::getInstance()->executeS(
        "SELECT *
            FROM `"._DB_PREFIX_."quantity_discount_rule_action`;"
    );

    foreach ($actions as $action) {
        if ($action['filter_by_price']) {
            if ($action['product_price_operator'] == 0) {
                Db::getInstance()->execute(
                    "UPDATE `"._DB_PREFIX_."quantity_discount_rule_action`
                    SET `product_price_from` = ".$action['product_price_amount'].",
                        `product_price_from_currency` = ".(int)$action['product_price_currency'].",
                        `product_price_from_tax` = '".(int)$action['product_price_tax']."',
                        `product_price_to` = 99999,
                        `product_price_to_currency` = ".(int)$action['product_price_currency'].",
                        `product_price_to_tax` = ".(int)$action['product_price_tax']."
                    WHERE `id_quantity_discount_rule_action` = ".(int)$action['id_quantity_discount_rule_action'].";"
                );
            } elseif ($action['product_price_operator'] == 1) {
                Db::getInstance()->execute(
                    "UPDATE `"._DB_PREFIX_."quantity_discount_rule_action`
                    SET `product_price_from` = ".$action['product_price_amount'].",
                        `product_price_from_currency` = ".(int)$action['product_price_currency'].",
                        `product_price_from_tax` = '".$action['product_price_tax']."',
                        `product_price_to` = ".$action['product_price_amount'].",
                        `product_price_to_currency` = ".(int)$action['product_price_currency'].",
                        `product_price_to_tax` = ".(int)$action['product_price_tax']."
                    WHERE `id_quantity_discount_rule_action` = ".(int)$action['id_quantity_discount_rule_action'].";"
                );
            } elseif ($action['product_price_operator'] == 2) {
                Db::getInstance()->execute(
                    "UPDATE `"._DB_PREFIX_."quantity_discount_rule_action`
                    SET `product_price_from` = 0,
                        `product_price_from_currency` = ".(int)$action['product_price_currency'].",
                        `product_price_from_tax` = '".$action['product_price_tax']."',
                        `product_price_to` = ".$action['product_price_amount'].",
                        `product_price_to_currency` = ".(int)$action['product_price_currency'].",
                        `product_price_to_tax` = ".(int)$action['product_price_tax']."
                    WHERE `id_quantity_discount_rule_action` = ".(int)$action['id_quantity_discount_rule_action'].";"
                );
            }
        }
    }

    $columnExists = Db::getInstance()->getRow(
        "SELECT *
        FROM information_schema.COLUMNS
        WHERE
            TABLE_SCHEMA = '"._DB_NAME_."'
        AND TABLE_NAME = '"._DB_PREFIX_."quantity_discount_rule_action'
        AND COLUMN_NAME = 'product_price_tax';"
    );

    if ($columnExists) {
        Db::getInstance()->execute(
            'ALTER TABLE `'._DB_PREFIX_.'quantity_discount_rule_action`
            DROP COLUMN product_price_operator,
            DROP COLUMN product_price_amount,
            DROP COLUMN product_price_currency,
            DROP COLUMN product_price_tax;'
        );
    }

    $columnExists = Db::getInstance()->getRow(
        "SELECT *
        FROM information_schema.COLUMNS
        WHERE
            TABLE_SCHEMA = '"._DB_NAME_."'
        AND TABLE_NAME = '"._DB_PREFIX_."quantity_discount_rule_condition'
        AND COLUMN_NAME = 'product_price_to_tax';"
    );

    if (!$columnExists) {
        Db::getInstance()->execute(
            "ALTER TABLE `"._DB_PREFIX_."quantity_discount_rule_condition`
            ADD `product_price_from` decimal(17,2) NOT NULL DEFAULT '0.00' AFTER `product_price_operator`,
            ADD `product_price_from_currency` int(10) unsigned NOT NULL AFTER `product_price_from`,
            ADD `product_price_from_tax` tinyint(1) NOT NULL AFTER `product_price_from_currency`,
            ADD `product_price_to` decimal(17,2) NOT NULL DEFAULT '0.00' AFTER `product_price_from_tax`,
            ADD `product_price_to_currency` int(10) unsigned NOT NULL AFTER `product_price_to`,
            ADD `product_price_to_tax` tinyint(1) NOT NULL AFTER `product_price_to_currency`;"
        );
    }

    $conditions = Db::getInstance()->executeS(
        "SELECT *
            FROM `"._DB_PREFIX_."quantity_discount_rule_condition`"
    );

    foreach ($conditions as $condition) {
        if ($condition['filter_by_price']) {
            if ($condition['product_price_operator'] == 0) {
                Db::getInstance()->execute(
                    "UPDATE `"._DB_PREFIX_."quantity_discount_rule_condition`
                    SET `product_price_from` = ".$condition['product_price_amount'].",
                        `product_price_from_currency` = ".(int)$condition['product_price_currency'].",
                        `product_price_from_tax` = '".(int)$condition['product_price_tax']."',
                        `product_price_to` = 99999,
                        `product_price_to_currency` = ".(int)$condition['product_price_currency'].",
                        `product_price_to_tax` = ".(int)$condition['product_price_tax']."
                    WHERE `id_quantity_discount_rule_condition` = ".(int)$condition['id_quantity_discount_rule_condition'].";"
                );
            } elseif ($condition['product_price_operator'] == 1) {
                Db::getInstance()->execute(
                    "UPDATE `"._DB_PREFIX_."quantity_discount_rule_condition`
                    SET `product_price_from` = ".$condition['product_price_amount'].",
                        `product_price_from_currency` = ".(int)$condition['product_price_currency'].",
                        `product_price_from_tax` = '".$condition['product_price_tax']."',
                        `product_price_to` = ".$condition['product_price_amount'].",
                        `product_price_to_currency` = ".(int)$condition['product_price_currency'].",
                        `product_price_to_tax` = ".(int)$condition['product_price_tax']."
                    WHERE `id_quantity_discount_rule_condition` = ".(int)$condition['id_quantity_discount_rule_condition'].";"
                );
            } elseif ($condition['product_price_operator'] == 2) {
                Db::getInstance()->execute(
                    "UPDATE `"._DB_PREFIX_."quantity_discount_rule_condition`
                    SET `product_price_from` = 0,
                        `product_price_from_currency` = ".(int)$condition['product_price_currency'].",
                        `product_price_from_tax` = '".$condition['product_price_tax']."',
                        `product_price_to` = ".$condition['product_price_amount'].",
                        `product_price_to_currency` = ".(int)$condition['product_price_currency'].",
                        `product_price_to_tax` = ".(int)$condition['product_price_tax']."
                    WHERE `id_quantity_discount_rule_condition` = ".(int)$condition['id_quantity_discount_rule_condition'].";"
                );
            }
        }
    }

    $columnExists = Db::getInstance()->getRow(
        "SELECT *
        FROM information_schema.COLUMNS
        WHERE
            TABLE_SCHEMA = '"._DB_NAME_."'
        AND TABLE_NAME = '"._DB_PREFIX_."quantity_discount_rule_condition'
        AND COLUMN_NAME = 'product_price_tax';"
    );

    if ($columnExists) {
        Db::getInstance()->execute(
            'ALTER TABLE `'._DB_PREFIX_.'quantity_discount_rule_condition`
            DROP COLUMN product_price_operator,
            DROP COLUMN product_price_amount,
            DROP COLUMN product_price_currency,
            DROP COLUMN product_price_tax;'
        );
    }

    return true;
}
