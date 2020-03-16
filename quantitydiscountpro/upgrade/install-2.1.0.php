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

function upgrade_module_2_1_0($module)
{
    Db::getInstance()->execute(
        "ALTER TABLE `"._DB_PREFIX_."quantity_discount_rule`
        ADD `modules_exceptions` text COLLATE 'utf8_general_ci' NULL AFTER `quantity_per_user`;"
    );

    Db::getInstance()->execute(
        "ALTER TABLE `"._DB_PREFIX_."quantity_discount_rule_action`
        ADD `products_nb_each` int(10) unsigned NOT NULL AFTER `nb_repetitions_custom`,
        ADD `reduction_percent_shipping` tinyint(1) NOT NULL DEFAULT '0' AFTER `reduction_shipping`,
        ADD `products_default_category` tinyint(1) unsigned NOT NULL AFTER `products_nb_each`,
        ADD `products_nb_same_category` tinyint(1) unsigned DEFAULT NULL AFTER `products_nb_each`,
        ADD `products_nb_same_attributes` tinyint(1) unsigned NOT NULL AFTER `products_nb_same_category`,
        ADD `group_products_by` varchar(255) COLLATE 'utf8_general_ci' NULL AFTER `products_default_category`,
        ADD `filter_by_product` tinyint(1) unsigned NOT NULL AFTER `group_products_by`,
        ADD `filter_by_attribute` tinyint(1) unsigned NOT NULL AFTER `filter_by_product`,
        ADD `filter_by_category` tinyint(1) unsigned NOT NULL AFTER `filter_by_attribute`,
        ADD `filter_by_supplier` tinyint(1) unsigned NOT NULL AFTER `filter_by_category`,
        ADD `filter_by_manufacturer` tinyint(1) unsigned NOT NULL AFTER `filter_by_supplier`,
        ADD `filter_by_price` tinyint(1) unsigned NOT NULL AFTER `filter_by_manufacturer`,
        ADD `filter_by_stock` tinyint(1) unsigned NOT NULL AFTER `filter_by_price`,
        ADD `reduction_buy_amount` decimal(17,2) NOT NULL AFTER `reduction_amount`,
        ADD `reduction_buy_amount_tax` tinyint(1) NOT NULL AFTER `reduction_buy_amount`,
        ADD `reduction_buy_over` decimal(17,2) NOT NULL AFTER `reduction_buy_amount`,
        ADD `stock_operator` tinyint(1) unsigned DEFAULT NULL AFTER `group_products_by`,
        ADD `stock` int(10) DEFAULT NULL AFTER `stock_operator`;"
    );

    Db::getInstance()->execute(
        "ALTER TABLE `"._DB_PREFIX_."quantity_discount_rule_condition`
        ADD `customer_default_group` tinyint(1) unsigned NOT NULL AFTER `id_customer`;"
    );

    Db::getInstance()->execute(
        "CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."quantity_discount_rule_action_manufacturer` (
            `id_quantity_discount_rule_action` int(10) unsigned NOT NULL,
            `id_quantity_discount_rule` int(10) unsigned NOT NULL,
            `id_manufacturer` int(10) unsigned NOT NULL,
            PRIMARY KEY `primary_"._DB_PREFIX_."quantity_discount_rule_action_manufacturer` (`id_quantity_discount_rule_action`, `id_quantity_discount_rule`, `id_manufacturer`),
            KEY `id_quantity_discount_rule_action` (`id_quantity_discount_rule_action`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8"
    );

    Db::getInstance()->execute(
        "CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."quantity_discount_rule_action_supplier` (
            `id_quantity_discount_rule_action` int(10) unsigned NOT NULL,
            `id_quantity_discount_rule` int(10) unsigned NOT NULL,
            `id_supplier` int(10) unsigned NOT NULL,
            PRIMARY KEY `primary_"._DB_PREFIX_."quantity_discount_rule_action_supplier` (`id_quantity_discount_rule_action`, `id_quantity_discount_rule`, `id_supplier`),
            KEY `id_quantity_discount_rule_action` (`id_quantity_discount_rule_action`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8"
    );

    Db::getInstance()->execute(
        "CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."quantity_discount_rule_condition_gender` (
            `id_quantity_discount_rule_condition` int(10) unsigned NOT NULL,
            `id_quantity_discount_rule` int(10) unsigned NOT NULL,
            `id_gender` int(10) unsigned NOT NULL,
            PRIMARY KEY `primary_"._DB_PREFIX_."quantity_discount_rule_condition_gender` (`id_quantity_discount_rule_condition`, `id_quantity_discount_rule`, `id_gender`),
            KEY `id_quantity_discount_rule_condition` (`id_quantity_discount_rule_condition`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8"
    );

    Db::getInstance()->execute(
        "CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."quantity_discount_rule_condition_currency` (
            `id_quantity_discount_rule_condition` int(10) unsigned NOT NULL,
            `id_quantity_discount_rule` int(10) unsigned NOT NULL,
            `id_currency` int(10) unsigned NOT NULL,
            PRIMARY KEY `primary_"._DB_PREFIX_."quantity_discount_rule_condition_currency` (`id_quantity_discount_rule_condition`, `id_quantity_discount_rule`, `id_currency`),
            KEY `id_quantity_discount_rule_condition` (`id_quantity_discount_rule_condition`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8"
    );


    $rules = Db::getInstance()->executeS(
        "SELECT *
        FROM `"._DB_PREFIX_."quantity_discount_rule`"
    );

    foreach ($rules as $rule) {
        //Get all conditions
        $conditions = Db::getInstance()->executeS(
            "SELECT *
            FROM `"._DB_PREFIX_."quantity_discount_rule_condition`
            WHERE `id_quantity_discount_rule` = ".(int)$rule['id_quantity_discount_rule']
        );

        $action = Db::getInstance()->getRow(
            "SELECT *
            FROM `"._DB_PREFIX_."quantity_discount_rule_action`
            WHERE `id_quantity_discount_rule` = ".(int)$rule['id_quantity_discount_rule']
        );

        foreach ($conditions as $condition) {
            if ((int)$condition['id_type'] == 16) {
                if ((int)$action['id_type'] == 2) {
                    Db::getInstance()->execute(
                        "UPDATE `"._DB_PREFIX_."quantity_discount_rule_action`
                        SET `id_type` = 6,
                            `products_nb_each` = ".(int)$condition['products_nb_each'].",
                            `group_products_by` = '".$condition['group_products_by']."',
                            `apply_discount_to_special` = ".(int)$condition['apply_discount_to_special'].",
                            `filter_by_product` = ".(int)$condition['filter_by_product'].",
                            `filter_by_attribute` = ".(int)$condition['filter_by_attribute'].",
                            `filter_by_category` = ".(int)$condition['filter_by_category'].",
                            `filter_by_supplier` = ".(int)$condition['filter_by_supplier'].",
                            `filter_by_manufacturer` = ".(int)$condition['filter_by_manufacturer'].",
                            `filter_by_price` = ".(int)$condition['filter_by_price'].",
                            `products_nb_same_attributes` = ".(int)$condition['products_nb_same_attributes']."
                        WHERE `id_quantity_discount_rule` = ".(int)$rule['id_quantity_discount_rule']
                    );
                } elseif ((int)$action['id_type'] == 3) {
                    Db::getInstance()->execute(
                        "UPDATE `"._DB_PREFIX_."quantity_discount_rule_action`
                        SET `id_type` = 7,
                            `products_nb_each` = ".(int)$condition['products_nb_each'].",
                            `group_products_by` = '".$condition['group_products_by']."',
                            `apply_discount_to_special` = ".(int)$condition['apply_discount_to_special'].",
                            `filter_by_product` = ".(int)$condition['filter_by_product'].",
                            `filter_by_attribute` = ".(int)$condition['filter_by_attribute'].",
                            `filter_by_category` = ".(int)$condition['filter_by_category'].",
                            `filter_by_supplier` = ".(int)$condition['filter_by_supplier'].",
                            `filter_by_manufacturer` = ".(int)$condition['filter_by_manufacturer'].",
                            `filter_by_price` = ".(int)$condition['filter_by_price'].",
                            `products_nb_same_attributes` = ".(int)$condition['products_nb_same_attributes']."
                        WHERE `id_quantity_discount_rule` = ".(int)$rule['id_quantity_discount_rule']
                    );
                } elseif ((int)$action['id_type'] == 4) {
                    Db::getInstance()->execute(
                        "UPDATE `"._DB_PREFIX_."quantity_discount_rule_action`
                        SET `id_type` = 8,
                            `products_nb_each` = ".(int)$condition['products_nb_each'].",
                            `group_products_by` = '".$condition['group_products_by']."',
                            `apply_discount_to_special` = ".(int)$condition['apply_discount_to_special'].",
                            `filter_by_product` = ".(int)$condition['filter_by_product'].",
                            `filter_by_attribute` = ".(int)$condition['filter_by_attribute'].",
                            `filter_by_category` = ".(int)$condition['filter_by_category'].",
                            `filter_by_supplier` = ".(int)$condition['filter_by_supplier'].",
                            `filter_by_manufacturer` = ".(int)$condition['filter_by_manufacturer'].",
                            `filter_by_price` = ".(int)$condition['filter_by_price'].",
                            `products_nb_same_attributes` = ".(int)$condition['products_nb_same_attributes']."
                        WHERE `id_quantity_discount_rule` = ".(int)$rule['id_quantity_discount_rule']
                    );
                }
            } elseif ((int)$condition['id_type'] == 24
                || (int)$condition['id_type'] == 27) {
                Db::getInstance()->execute(
                    "UPDATE `"._DB_PREFIX_."quantity_discount_rule_action`
                    SET `id_type` = 24,
                        `filter_by_category` = 1,
                        `group_products_by` = 'category'
                    WHERE `id_quantity_discount_rule` = ".(int)$rule['id_quantity_discount_rule']
                );

                Db::getInstance()->execute(
                    "INSERT INTO `"._DB_PREFIX_."quantity_discount_rule_action` (`id_quantity_discount_rule`, `id_type`, `products_nb_each`, `group_products_by`, `filter_by_product`, `filter_by_attribute`, `filter_by_category`, `filter_by_supplier`, `filter_by_manufacturer`, `filter_by_price`, `products_nb_same_attributes`)
                    VALUES (".(int)$rule['id_quantity_discount_rule'].", 22, ".(int)$condition['products_nb_each'].", 'category', 0, 0, ".(int)$condition['filter_by_category'].", 0, 0, 0, 0)"
                );
                $id = (int)Db::getInstance()->Insert_ID();

                $multiselects = array('product', 'attribute', 'category', 'supplier', 'manufacturer');
                foreach ($multiselects as $multiselect) {
                    if ((int)$condition['filter_by_'.$multiselect] == 1) {
                        $values = Db::getInstance()->executeS(
                            "SELECT *
                            FROM `"._DB_PREFIX_."quantity_discount_rule_condition_".$multiselect."`
                            WHERE `id_quantity_discount_rule_condition` = ".(int)$condition['id_quantity_discount_rule_condition']
                        );

                        foreach ($values as $value) {
                            Db::getInstance()->execute(
                                "INSERT INTO `"._DB_PREFIX_."quantity_discount_rule_action_".$multiselect."` (`id_quantity_discount_rule_action`, `id_quantity_discount_rule`, `id_".$multiselect."`)
                                VALUES (".(int)$id.", ".(int)$action['id_quantity_discount_rule'].", ".(int)$value['id_'.$multiselect].")"
                            );
                        }
                    }
                }
            } elseif ((int)$condition['id_type'] == 17
                || (int)$condition['id_type'] == 26) {
                if ((int)$action['id_type'] == 2) {
                    Db::getInstance()->execute(
                        "UPDATE `"._DB_PREFIX_."quantity_discount_rule_action`
                        SET `id_type` = 12,
                            `products_nb_each` = ".(int)$condition['products_nb_each'].",
                            `group_products_by` = 'category',
                            `apply_discount_to_special` = ".(int)$condition['apply_discount_to_special'].",
                            `filter_by_product` = ".(int)$condition['filter_by_product'].",
                            `filter_by_attribute` = ".(int)$condition['filter_by_attribute'].",
                            `filter_by_category` = ".(int)$condition['filter_by_category'].",
                            `filter_by_supplier` = ".(int)$condition['filter_by_supplier'].",
                            `filter_by_manufacturer` = ".(int)$condition['filter_by_manufacturer'].",
                            `filter_by_price` = ".(int)$condition['filter_by_price'].",
                            `products_nb_same_attributes` = ".(int)$condition['products_nb_same_attributes']."
                        WHERE `id_quantity_discount_rule` = ".(int)$rule['id_quantity_discount_rule']
                    );
                } elseif ((int)$action['id_type'] == 3) {
                    Db::getInstance()->execute(
                        "UPDATE `"._DB_PREFIX_."quantity_discount_rule_action`
                        SET `id_type` = 13,
                            `products_nb_each` = ".(int)$condition['products_nb_each'].",
                            `group_products_by` = 'category',
                            `apply_discount_to_special` = ".(int)$condition['apply_discount_to_special'].",
                            `filter_by_product` = ".(int)$condition['filter_by_product'].",
                            `filter_by_attribute` = ".(int)$condition['filter_by_attribute'].",
                            `filter_by_category` = ".(int)$condition['filter_by_category'].",
                            `filter_by_supplier` = ".(int)$condition['filter_by_supplier'].",
                            `filter_by_manufacturer` = ".(int)$condition['filter_by_manufacturer'].",
                            `filter_by_price` = ".(int)$condition['filter_by_price'].",
                            `products_nb_same_attributes` = ".(int)$condition['products_nb_same_attributes']."
                        WHERE `id_quantity_discount_rule` = ".(int)$rule['id_quantity_discount_rule']
                    );
                } elseif ((int)$action['id_type'] == 4) {
                    Db::getInstance()->execute(
                        "UPDATE `"._DB_PREFIX_."quantity_discount_rule_action`
                        SET `id_type` = 14,
                            `products_nb_each` = ".(int)$condition['products_nb_each'].",
                            `group_products_by` = 'category',
                            `apply_discount_to_special` = ".(int)$condition['apply_discount_to_special'].",
                            `filter_by_product` = ".(int)$condition['filter_by_product'].",
                            `filter_by_attribute` = ".(int)$condition['filter_by_attribute'].",
                            `filter_by_category` = ".(int)$condition['filter_by_category'].",
                            `filter_by_supplier` = ".(int)$condition['filter_by_supplier'].",
                            `filter_by_manufacturer` = ".(int)$condition['filter_by_manufacturer'].",
                            `filter_by_price` = ".(int)$condition['filter_by_price'].",
                            `products_nb_same_attributes` = ".(int)$condition['products_nb_same_attributes']."
                        WHERE `id_quantity_discount_rule` = ".(int)$rule['id_quantity_discount_rule']
                    );
                }
            } elseif ((int)$condition['id_type'] == 29) {
                if ((int)$action['id_type'] == 2) {
                    Db::getInstance()->execute(
                        "UPDATE `"._DB_PREFIX_."quantity_discount_rule_action`
                        SET `id_type` = 18,
                            `products_nb_each` = ".(int)$condition['products_nb_each'].",
                            `apply_discount_to_nb` = 0,
                            `group_products_by` = '".$condition['group_products_by']."',
                            `apply_discount_to_special` = ".(int)$condition['apply_discount_to_special'].",
                            `filter_by_product` = ".(int)$condition['filter_by_product'].",
                            `filter_by_attribute` = ".(int)$condition['filter_by_attribute'].",
                            `filter_by_category` = ".(int)$condition['filter_by_category'].",
                            `filter_by_supplier` = ".(int)$condition['filter_by_supplier'].",
                            `filter_by_manufacturer` = ".(int)$condition['filter_by_manufacturer'].",
                            `filter_by_price` = ".(int)$condition['filter_by_price'].",
                            `products_nb_same_attributes` = ".(int)$condition['products_nb_same_attributes']."
                        WHERE `id_quantity_discount_rule` = ".(int)$rule['id_quantity_discount_rule']
                    );
                } elseif ((int)$action['id_type'] == 3) {
                    Db::getInstance()->execute(
                        "UPDATE `"._DB_PREFIX_."quantity_discount_rule_action`
                        SET `id_type` = 19,
                            `products_nb_each` = ".(int)$condition['products_nb_each'].",
                            `apply_discount_to_nb` = 0,
                            `group_products_by` = '".$condition['group_products_by']."',
                            `apply_discount_to_special` = ".(int)$condition['apply_discount_to_special'].",
                            `filter_by_product` = ".(int)$condition['filter_by_product'].",
                            `filter_by_attribute` = ".(int)$condition['filter_by_attribute'].",
                            `filter_by_category` = ".(int)$condition['filter_by_category'].",
                            `filter_by_supplier` = ".(int)$condition['filter_by_supplier'].",
                            `filter_by_manufacturer` = ".(int)$condition['filter_by_manufacturer'].",
                            `filter_by_price` = ".(int)$condition['filter_by_price'].",
                            `products_nb_same_attributes` = ".(int)$condition['products_nb_same_attributes']."
                        WHERE `id_quantity_discount_rule` = ".(int)$rule['id_quantity_discount_rule']
                    );
                } elseif ((int)$action['id_type'] == 4) {
                    Db::getInstance()->execute(
                        "UPDATE `"._DB_PREFIX_."quantity_discount_rule_action`
                        SET `id_type` = 20,
                            `products_nb_each` = ".(int)$condition['products_nb_each'].",
                            `apply_discount_to_nb` = 0,
                            `group_products_by` = '".$condition['group_products_by']."',
                            `apply_discount_to_special` = ".(int)$condition['apply_discount_to_special'].",
                            `filter_by_product` = ".(int)$condition['filter_by_product'].",
                            `filter_by_attribute` = ".(int)$condition['filter_by_attribute'].",
                            `filter_by_category` = ".(int)$condition['filter_by_category'].",
                            `filter_by_supplier` = ".(int)$condition['filter_by_supplier'].",
                            `filter_by_manufacturer` = ".(int)$condition['filter_by_manufacturer'].",
                            `filter_by_price` = ".(int)$condition['filter_by_price'].",
                            `products_nb_same_attributes` = ".(int)$condition['products_nb_same_attributes']."
                        WHERE `id_quantity_discount_rule` = ".(int)$rule['id_quantity_discount_rule']
                    );
                }
            } else {
                if ((int)$action['id_type'] == 1) {
                    Db::getInstance()->execute(
                        "UPDATE `"._DB_PREFIX_."quantity_discount_rule_action`
                        SET `id_type` = 5,
                            `reduction_percent` = 100
                        WHERE `id_quantity_discount_rule` = ".(int)$rule['id_quantity_discount_rule']
                    );
                } elseif ((int)$action['id_type'] == 2 && $action['apply_discount_to'] == 'product') {
                    Db::getInstance()->execute(
                        "UPDATE `"._DB_PREFIX_."quantity_discount_rule_action`
                        SET `id_type` = 12,
                            `products_nb_each` = 0,
                            `group_products_by` = '".$condition['group_products_by']."',
                            `apply_discount_to_special` = ".(int)$condition['apply_discount_to_special'].",
                            `filter_by_product` = ".(int)$condition['filter_by_product'].",
                            `filter_by_attribute` = ".(int)$condition['filter_by_attribute'].",
                            `filter_by_category` = ".(int)$condition['filter_by_category'].",
                            `filter_by_supplier` = ".(int)$condition['filter_by_supplier'].",
                            `filter_by_manufacturer` = ".(int)$condition['filter_by_manufacturer'].",
                            `filter_by_price` = ".(int)$condition['filter_by_price'].",
                            `products_nb_same_attributes` = ".(int)$condition['products_nb_same_attributes'].",
                            `nb_repetitions` = 'custom',
                            `nb_repetitions_custom` = ".(int)$action['apply_discount_to_nb']."
                        WHERE `id_quantity_discount_rule` = ".(int)$rule['id_quantity_discount_rule']
                    );
                } elseif ((int)$action['id_type'] == 3 && $action['apply_discount_to'] == 'product') {
                    Db::getInstance()->execute(
                        "UPDATE `"._DB_PREFIX_."quantity_discount_rule_action`
                        SET `id_type` = 13,
                            `products_nb_each` = 0,
                            `group_products_by` = '".$condition['group_products_by']."',
                            `apply_discount_to_special` = ".(int)$condition['apply_discount_to_special'].",
                            `filter_by_product` = ".(int)$condition['filter_by_product'].",
                            `filter_by_attribute` = ".(int)$condition['filter_by_attribute'].",
                            `filter_by_category` = ".(int)$condition['filter_by_category'].",
                            `filter_by_supplier` = ".(int)$condition['filter_by_supplier'].",
                            `filter_by_manufacturer` = ".(int)$condition['filter_by_manufacturer'].",
                            `filter_by_price` = ".(int)$condition['filter_by_price'].",
                            `products_nb_same_attributes` = ".(int)$condition['products_nb_same_attributes'].",
                            `nb_repetitions` = 'custom',
                            `nb_repetitions_custom` = ".(int)$action['apply_discount_to_nb']."
                        WHERE `id_quantity_discount_rule` = ".(int)$rule['id_quantity_discount_rule']
                    );
                } elseif ((int)$action['id_type'] == 4 && $action['apply_discount_to'] == 'product') {
                    Db::getInstance()->execute(
                        "UPDATE `"._DB_PREFIX_."quantity_discount_rule_action`
                        SET `id_type` = 14,
                            `products_nb_each` = 0,
                            `group_products_by` = '".$condition['group_products_by']."',
                            `apply_discount_to_special` = ".(int)$condition['apply_discount_to_special'].",
                            `filter_by_product` = ".(int)$condition['filter_by_product'].",
                            `filter_by_attribute` = ".(int)$condition['filter_by_attribute'].",
                            `filter_by_category` = ".(int)$condition['filter_by_category'].",
                            `filter_by_supplier` = ".(int)$condition['filter_by_supplier'].",
                            `filter_by_manufacturer` = ".(int)$condition['filter_by_manufacturer'].",
                            `filter_by_price` = ".(int)$condition['filter_by_price'].",
                            `products_nb_same_attributes` = ".(int)$condition['products_nb_same_attributes'].",
                            `nb_repetitions` = 'custom',
                            `nb_repetitions_custom` = ".(int)$action['apply_discount_to_nb']."
                        WHERE `id_quantity_discount_rule` = ".(int)$rule['id_quantity_discount_rule']
                    );
                }
            }

            if ((int)$condition['id_type'] == 16
                || (int)$condition['id_type'] == 17
                || (int)$condition['id_type'] == 26
                || (int)$condition['id_type'] == 29) {
                $multiselects = array('product', 'attribute', 'category', 'supplier', 'manufacturer');
                foreach ($multiselects as $multiselect) {
                    Db::getInstance()->execute(
                        "DELETE FROM `"._DB_PREFIX_."quantity_discount_rule_action_".$multiselect."`
                        WHERE `id_quantity_discount_rule` = ".(int)$rule['id_quantity_discount_rule']
                    );

                    if ((int)$condition['filter_by_'.$multiselect] == 1) {
                        $values = Db::getInstance()->executeS(
                            "SELECT *
                            FROM `"._DB_PREFIX_."quantity_discount_rule_condition_".$multiselect."`
                            WHERE `id_quantity_discount_rule_condition` = ".(int)$condition['id_quantity_discount_rule_condition']
                        );

                        foreach ($values as $value) {
                            Db::getInstance()->execute(
                                "INSERT INTO `"._DB_PREFIX_."quantity_discount_rule_action_".$multiselect."` (`id_quantity_discount_rule_action`, `id_quantity_discount_rule`, `id_".$multiselect."`)
                                VALUES (".(int)$action['id_quantity_discount_rule_action'].", ".(int)$action['id_quantity_discount_rule'].", ".(int)$value['id_'.$multiselect].")"
                            );
                        }
                    }
                }
            }

            if ((int)$condition['id_type'] == 16
                || (int)$condition['id_type'] == 17
                || (int)$condition['id_type'] == 26
                || (int)$condition['id_type'] == 29
                || (int)$condition['id_type'] == 24
                || (int)$condition['id_type'] == 27) {
                Db::getInstance()->execute(
                    "DELETE FROM `"._DB_PREFIX_."quantity_discount_rule_condition`
                    WHERE `id_quantity_discount_rule_condition` = ".(int)$condition['id_quantity_discount_rule_condition']
                );
            }
        }
    }

    Db::getInstance()->execute(
        "UPDATE `"._DB_PREFIX_."quantity_discount_rule`
        SET `active` = 0;"
    );

    Db::getInstance()->execute(
        "ALTER TABLE `"._DB_PREFIX_."quantity_discount_rule_condition`
        ADD `filter_by_stock` tinyint(1) unsigned NOT NULL AFTER `filter_by_price`,
        ADD `product_stock_operator` tinyint(1) unsigned DEFAULT NULL AFTER `product_price_tax`,
        ADD `product_stock_amount` int(10) DEFAULT NULL AFTER `product_stock_operator`,
        DROP `products_nb_each`;"
    );

    Db::getInstance()->execute(
        "DROP TABLE IF EXISTS `"._DB_PREFIX_."quantity_discount_rule_messages`, `"._DB_PREFIX_."quantity_discount_rule_messages_lang`;"
    );

    Db::getInstance()->execute(
        "CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."quantity_discount_rule_message` (
            `id_quantity_discount_rule_message` int(10) unsigned NOT NULL AUTO_INCREMENT,
            `id_quantity_discount_rule` int(10) unsigned NOT NULL,
            `hook_name` varchar(255) NOT NULL,
            PRIMARY KEY `id_quantity_discount_rule_message` (`id_quantity_discount_rule_message`),
            INDEX `id_quantity_discount_rule` (`id_quantity_discount_rule`),
            INDEX `id_quantity_discount_rule_hook_name` (`id_quantity_discount_rule`, `hook_name`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8"
    );

    Db::getInstance()->execute(
        "CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."quantity_discount_rule_message_lang` (
            `id_quantity_discount_rule_message` int(10) unsigned NOT NULL,
            `id_lang` int(10) unsigned NOT NULL,
            `message` text NOT NULL,
            PRIMARY KEY `id_quantity_discount_rule_message_id_lang` (`id_quantity_discount_rule_message`, `id_lang`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8"
    );

    $module->registerHook('displayLeftColumn');
    $module->registerHook('displayLeftColumnProduct');
    $module->registerHook('displayRightColumn');
    $module->registerHook('displayRightColumnProduct');
    $module->registerHook('displayproductButtons');
    $module->registerHook('displayProductTab');
    $module->registerHook('displayProductTabContent');
    $module->registerHook('displayFooterProduct');
    $module->registerHook('displayProductPriceBlock');
    $module->registerHook('shoppingCart');
    $module->registerHook('shoppingCartExtra');
    $module->registerHook('displayBeforeCarrier');
    $module->registerHook('displayPaymentTop');
    $module->registerHook('displayTop');
    $module->registerHook('displayFooter');
    $module->registerHook('displayBanner');
    $module->registerHook('actionValidateOrder');
    $module->registerHook('actionAuthentication');
    $module->registerHook('displayQuantityDiscountProCustom1');
    $module->registerHook('displayQuantityDiscountProCustom2');
    $module->registerHook('displayQuantityDiscountProCustom3');
    $module->registerHook('displayQuantityDiscountProCustom4');
    $module->registerHook('displayQuantityDiscountProCustom5');

    $module->copyOverrideFolder();
    $module->uninstallOverrides();
    $module->installOverrides();

    return true;
}
