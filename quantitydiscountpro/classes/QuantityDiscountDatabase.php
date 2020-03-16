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

class QuantityDiscountDatabase extends ObjectModel
{
    public static function createTables()
    {
        $result = true;

        $db = Db::getInstance();

        $result &= $db->execute(
            "CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."quantity_discount_rule` (
                `id_quantity_discount_rule` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `active` tinyint(1) NOT NULL,
                `description` text,
                `id_family` int(10) DEFAULT NULL,
                `code` varchar(254) DEFAULT NULL,
                `code_prefix` varchar(254) DEFAULT NULL,
                `date_from` datetime DEFAULT NULL,
                `date_to` datetime DEFAULT NULL,
                `priority` int(10) DEFAULT NULL,
                `execute_other_rules` tinyint(1) NOT NULL,
                `compatible_cart_rules` tinyint(1) NOT NULL,
                `compatible_qdp_rules` tinyint(1) NOT NULL,
                `apply_products_already_discounted` tinyint(1) NOT NULL,
                `quantity` int(10) unsigned NOT NULL DEFAULT '0',
                `quantity_per_user` int(10) unsigned NOT NULL DEFAULT '0',
                `modules_exceptions` text,
                `highlight` tinyint(1) NOT NULL,
                `date_add` datetime DEFAULT NULL,
                `date_upd` datetime DEFAULT NULL,
                PRIMARY KEY (`id_quantity_discount_rule`)
            ) ENGINE="._MYSQL_ENGINE_." DEFAULT CHARSET=utf8;"
        );

        $result &= $db->execute(
            "CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."quantity_discount_rule_action` (
                `id_quantity_discount_rule_action` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `id_quantity_discount_rule` int(10) unsigned NOT NULL,
                `id_type` int(10) unsigned NOT NULL,
                `free_shipping` tinyint(1) NOT NULL DEFAULT '0',
                `reduction_amount` decimal(17,2) NOT NULL,
                `reduction_buy_amount` decimal(17,2) NOT NULL,
                `reduction_buy_amount_tax` tinyint(1) NOT NULL,
                `reduction_buy_over` decimal(17,2) NOT NULL,
                `reduction_currency` int(10) unsigned NOT NULL,
                `reduction_tax` tinyint(1) NOT NULL,
                `reduction_percent` decimal(5,2) NOT NULL,
                `reduction_percent_tax` tinyint(1) NOT NULL,
                `reduction_shipping` tinyint(1) NOT NULL DEFAULT '0',
                `reduction_percent_shipping` tinyint(1) NOT NULL DEFAULT '0',
                `reduction_percent_discount` tinyint(1) NOT NULL DEFAULT '0',
                `reduction_max_amount` decimal(17,2) NOT NULL DEFAULT '0.00',
                `reduction_product_max_amount` decimal(17,2) NOT NULL DEFAULT '0.00',
                `reduction_max_currency` int(10) unsigned NOT NULL,
                `spent_amount_from` decimal(17,2) NOT NULL DEFAULT '0.00',
                `spent_amount_to` decimal(17,2) NOT NULL DEFAULT '0.00',
                `spent_currency` int(10) unsigned NOT NULL,
                `spent_tax` tinyint(1) NOT NULL,
                `product_price_from` decimal(17,2) NOT NULL DEFAULT '0.00',
                `product_price_from_currency` int(10) unsigned NOT NULL,
                `product_price_from_tax` tinyint(1) NOT NULL,
                `product_price_to` decimal(17,2) NOT NULL DEFAULT '0.00',
                `product_price_to_currency` int(10) unsigned NOT NULL,
                `product_price_to_tax` tinyint(1) NOT NULL,
                `apply_discount_sort` enum('cheapest','expensive') NOT NULL,
                `apply_discount_to_nb` int(10) unsigned NOT NULL,
                `apply_discount_to_all` tinyint(1) unsigned NOT NULL,
                `apply_discount_to_special` tinyint(1) unsigned NOT NULL,
                `apply_discount_to_regular_price` tinyint(1) unsigned NOT NULL,
                `apply_discount_to_stock` tinyint(1) unsigned NOT NULL,
                `nb_repetitions` varchar(255) NOT NULL,
                `products_nb_same_attributes` tinyint(1) unsigned NOT NULL,
                `nb_repetitions_custom` int(10) unsigned NOT NULL,
                `products_nb_each` int(10) unsigned NOT NULL,
                `products_default_category` tinyint(1) unsigned NOT NULL,
                `group_products_by` varchar(255) DEFAULT NULL,
                `stock_operator` tinyint(1) unsigned DEFAULT NULL,
                `stock` int(10) DEFAULT NULL,
                `gift_product` int(10) unsigned NULL,
                `gift_product_attribute` int(10) unsigned NULL,
                `filter_by_product` tinyint(1) unsigned NOT NULL,
                `filter_by_attribute` tinyint(1) unsigned NOT NULL,
                `filter_by_feature` tinyint(1) unsigned NOT NULL,
                `filter_by_category` tinyint(1) unsigned NOT NULL,
                `filter_by_supplier` tinyint(1) unsigned NOT NULL,
                `filter_by_manufacturer` tinyint(1) unsigned NOT NULL,
                `filter_by_price` tinyint(1) unsigned NOT NULL,
                `filter_by_stock` tinyint(1) unsigned NOT NULL,
                `filter_by_carrier` tinyint(1) unsigned NOT NULL,
                PRIMARY KEY (`id_quantity_discount_rule_action`)
            ) ENGINE="._MYSQL_ENGINE_." DEFAULT CHARSET=utf8;"
        );

        $result &= $db->execute(
            "CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."quantity_discount_rule_action_attribute` (
                `id_quantity_discount_rule_action` int(10) unsigned NOT NULL,
                `id_quantity_discount_rule` int(10) unsigned NOT NULL,
                `id_attribute` int(10) unsigned NOT NULL,
                PRIMARY KEY `primary_ps_quantity_discount_rule_action_attribute` (`id_quantity_discount_rule_action`, `id_quantity_discount_rule`, `id_attribute`),
                KEY `id_quantity_discount_rule_action` (`id_quantity_discount_rule_action`)
            ) ENGINE="._MYSQL_ENGINE_." DEFAULT CHARSET=utf8;"
        );

        $result &= $db->execute(
            "CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."quantity_discount_rule_action_feature` (
                `id_quantity_discount_rule_action` int(10) unsigned NOT NULL,
                `id_quantity_discount_rule` int(10) unsigned NOT NULL,
                `id_feature` int(10) unsigned NOT NULL,
                PRIMARY KEY `primary_ps_quantity_discount_rule_action_feature` (`id_quantity_discount_rule_action`, `id_quantity_discount_rule`, `id_feature`),
                KEY `id_quantity_discount_rule_action` (`id_quantity_discount_rule_action`)
            ) ENGINE="._MYSQL_ENGINE_." DEFAULT CHARSET=utf8;"
        );

        $result &= $db->execute(
            "CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."quantity_discount_rule_action_category` (
                `id_quantity_discount_rule_action` int(10) unsigned NOT NULL,
                `id_quantity_discount_rule` int(10) unsigned NOT NULL,
                `id_category` int(10) unsigned NOT NULL,
                PRIMARY KEY `primary_ps_quantity_discount_rule_action_category` (`id_quantity_discount_rule_action`, `id_quantity_discount_rule`, `id_category`),
                KEY `id_quantity_discount_rule_action` (`id_quantity_discount_rule_action`)
            ) ENGINE="._MYSQL_ENGINE_." DEFAULT CHARSET=utf8;"
        );

        $result &= $db->execute(
            "CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."quantity_discount_rule_action_product` (
                `id_quantity_discount_rule_action` int(10) unsigned NOT NULL,
                `id_quantity_discount_rule` int(10) unsigned NOT NULL,
                `id_product` int(10) unsigned NOT NULL,
                PRIMARY KEY `primary_ps_quantity_discount_rule_action_product` (`id_quantity_discount_rule_action`, `id_quantity_discount_rule`, `id_product`),
                KEY `id_quantity_discount_rule_action` (`id_quantity_discount_rule_action`)
            ) ENGINE="._MYSQL_ENGINE_." DEFAULT CHARSET=utf8;"
        );

        $result &= $db->execute(
            "CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."quantity_discount_rule_action_manufacturer` (
                `id_quantity_discount_rule_action` int(10) unsigned NOT NULL,
                `id_quantity_discount_rule` int(10) unsigned NOT NULL,
                `id_manufacturer` int(10) unsigned NOT NULL,
                PRIMARY KEY `primary_ps_quantity_discount_rule_action_manufacturer` (`id_quantity_discount_rule_action`, `id_quantity_discount_rule`, `id_manufacturer`),
                KEY `id_quantity_discount_rule_action` (`id_quantity_discount_rule_action`)
            ) ENGINE="._MYSQL_ENGINE_." DEFAULT CHARSET=utf8;"
        );

        $result &= $db->execute(
            "CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."quantity_discount_rule_action_supplier` (
                `id_quantity_discount_rule_action` int(10) unsigned NOT NULL,
                `id_quantity_discount_rule` int(10) unsigned NOT NULL,
                `id_supplier` int(10) unsigned NOT NULL,
                PRIMARY KEY `primary_ps_quantity_discount_rule_action_supplier` (`id_quantity_discount_rule_action`, `id_quantity_discount_rule`, `id_supplier`),
                KEY `id_quantity_discount_rule_action` (`id_quantity_discount_rule_action`)
            ) ENGINE="._MYSQL_ENGINE_." DEFAULT CHARSET=utf8;"
        );

        $result &= $db->execute(
            "CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."quantity_discount_rule_action_carrier` (
                `id_quantity_discount_rule_action` int(10) unsigned NOT NULL,
                `id_quantity_discount_rule` int(10) unsigned NOT NULL,
                `id_carrier` int(10) unsigned NOT NULL,
                PRIMARY KEY `primary_ps_quantity_discount_rule_action_carrier` (`id_quantity_discount_rule_action`, `id_quantity_discount_rule`, `id_carrier`),
                KEY `id_quantity_discount_rule_action` (`id_quantity_discount_rule_action`)
            ) ENGINE="._MYSQL_ENGINE_." DEFAULT CHARSET=utf8;"
        );

        $result &= $db->execute(
            "CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."quantity_discount_rule_group` (
              `id_quantity_discount_rule_group` int(10) unsigned NOT NULL AUTO_INCREMENT,
              `id_quantity_discount_rule` int(10) unsigned NOT NULL,
              PRIMARY KEY (`id_quantity_discount_rule_group`)
            ) ENGINE="._MYSQL_ENGINE_." DEFAULT CHARSET=utf8;"
        );

        $result &= $db->execute(
            "CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."quantity_discount_rule_cart` (
                `id_cart` int(10) unsigned NOT NULL,
                `id_quantity_discount_rule` int(10) unsigned NOT NULL,
                `id_cart_rule` int(10) unsigned NOT NULL,
                `id_action_type` int(10) unsigned NULL,
                PRIMARY KEY (`id_cart`,`id_quantity_discount_rule`,`id_cart_rule`),
                KEY `id_cart` (`id_cart`),
                KEY `id_cart_id_cart_rule` (`id_cart`,`id_cart_rule`)
            ) ENGINE="._MYSQL_ENGINE_." DEFAULT CHARSET=utf8;"
        );

        $result &= $db->execute(
            "CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."quantity_discount_rule_cart_product` (
              `id_cart` int(10) unsigned NOT NULL,
              `id_quantity_discount_rule` int(10) unsigned NOT NULL,
              `id_product` int(10) unsigned NOT NULL,
              `id_product_attribute` int(10) unsigned DEFAULT NULL,
              `quantity` int(10) unsigned DEFAULT NULL,
              KEY `id_cart_id_quantity_discount_rule` (`id_cart`,`id_quantity_discount_rule`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;"
        );

        $result &= $db->execute(
            "CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."quantity_discount_rule_condition` (
                `id_quantity_discount_rule_condition` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `id_quantity_discount_rule` int(10) unsigned NOT NULL,
                `id_quantity_discount_rule_group` int(10) unsigned NOT NULL,
                `id_type` int(10) unsigned NOT NULL,
                `id_customer` int(10) unsigned DEFAULT NULL,
                `customer_default_group` tinyint(1) unsigned DEFAULT NULL,
                `customer_newsletter` tinyint(1) unsigned DEFAULT NULL,
                `customer_vat` tinyint(1) unsigned DEFAULT NULL,
                `customer_signedup_date_from` datetime DEFAULT NULL,
                `customer_signedup_date_to` datetime DEFAULT NULL,
                `customer_signedup_days` int(10) unsigned NULL,
                `customer_orders_nb_operator` tinyint(1) unsigned DEFAULT NULL,
                `customer_orders_nb` int(10) unsigned DEFAULT NULL,
                `customer_orders_nb_date_from` datetime DEFAULT NULL,
                `customer_orders_nb_date_to` datetime DEFAULT NULL,
                `customer_orders_nb_days` int(10) unsigned DEFAULT NULL,
                `customer_orders_nb_prod_operator` tinyint(1) unsigned DEFAULT NULL,
                `customer_orders_nb_prod` int(10) unsigned DEFAULT NULL,
                `customer_orders_amount_operator` tinyint(1) unsigned DEFAULT NULL,
                `customer_orders_amount` decimal(17,2) DEFAULT NULL,
                `customer_orders_amount_currency` int(10) unsigned DEFAULT NULL,
                `customer_orders_amount_tax` tinyint(1) unsigned DEFAULT NULL,
                `customer_orders_amount_shipping` tinyint(1) unsigned DEFAULT NULL,
                `customer_orders_amount_discount` tinyint(1) unsigned DEFAULT NULL,
                `customer_orders_amount_date_from` datetime DEFAULT NULL,
                `customer_orders_amount_date_to` datetime DEFAULT NULL,
                `customer_orders_amount_days` int(10) unsigned DEFAULT NULL,
                `customer_orders_amount_orders` int(10) unsigned DEFAULT NULL,
                `customer_orders_amount_interval` tinyint(1) unsigned DEFAULT NULL,
                `customer_first_order` tinyint(1) unsigned DEFAULT NULL,
                `customer_membership_operator` tinyint(1) unsigned DEFAULT NULL,
                `customer_membership` int(10) unsigned NULL,
                `customer_birthday` tinyint(1) unsigned DEFAULT NULL,
                `customer_years_from` int(10) unsigned NULL,
                `customer_years_to` int(10) unsigned NULL,
                `cart_amount_operator` tinyint(1) unsigned DEFAULT NULL,
                `cart_amount` decimal(17,2) DEFAULT NULL,
                `cart_amount_currency` int(10) unsigned DEFAULT NULL,
                `cart_amount_tax` tinyint(1) unsigned DEFAULT NULL,
                `cart_amount_shipping` tinyint(1) unsigned DEFAULT NULL,
                `cart_amount_discount` tinyint(1) unsigned DEFAULT NULL,
                `cart_weight_operator` tinyint(1) unsigned DEFAULT NULL,
                `cart_weight` decimal(17,2) DEFAULT NULL,
                `products_all_met` tinyint(1) unsigned NULL,
                `products_nb_all_met` tinyint(1) unsigned DEFAULT NULL,
                `products_operator` tinyint(1) unsigned DEFAULT NULL,
                `products_amount` decimal(17,2) DEFAULT NULL,
                `products_amount_currency` int(10) unsigned DEFAULT NULL,
                `products_amount_tax` tinyint(1) unsigned DEFAULT NULL,
                `products_nb_operator` tinyint(1) unsigned DEFAULT NULL,
                `products_nb` int(10) unsigned DEFAULT NULL,
                `products_nb_same` tinyint(1) unsigned DEFAULT NULL,
                `products_nb_same_attributes` tinyint(1) unsigned DEFAULT NULL,
                `products_nb_same_category` tinyint(1) unsigned DEFAULT NULL,
                `products_nb_dif_operator` tinyint(1) unsigned DEFAULT NULL,
                `products_nb_dif` int(10) unsigned DEFAULT NULL,
                `products_nb_dif_cat_operator` tinyint(1) unsigned DEFAULT NULL,
                `products_nb_dif_cat` int(10) unsigned DEFAULT NULL,
                `product_price_from` decimal(17,2) NOT NULL DEFAULT '0.00',
                `product_price_from_currency` int(10) unsigned NOT NULL,
                `product_price_from_tax` tinyint(1) NOT NULL,
                `product_price_to` decimal(17,2) NOT NULL DEFAULT '0.00',
                `product_price_to_currency` int(10) unsigned NOT NULL,
                `product_price_to_tax` tinyint(1) NOT NULL,
                `product_stock_operator` tinyint(1) unsigned DEFAULT NULL,
                `product_stock_amount` int(10) DEFAULT NULL,
                `products_default_category` tinyint(1) unsigned NOT NULL,
                `apply_discount_to_special` tinyint(1) NOT NULL,
                `group_products_by` varchar(255) DEFAULT NULL,
                `filter_by_product` tinyint(1) unsigned NULL,
                `filter_by_attribute` tinyint(1) unsigned NULL,
                `filter_by_feature` tinyint(1) unsigned NOT NULL,
                `filter_by_category` tinyint(1) unsigned NULL,
                `filter_by_supplier` tinyint(1) unsigned NULL,
                `filter_by_manufacturer` tinyint(1) unsigned NULL,
                `filter_by_price` tinyint(1) unsigned NULL,
                `filter_by_stock` tinyint(1) unsigned NULL,
                `schedule` text COLLATE 'utf8_general_ci' NULL,
                `url_string` varchar(255) COLLATE 'utf8_general_ci' NULL,
                PRIMARY KEY (`id_quantity_discount_rule_condition`),
                KEY `id_quantity_discount_rule` (`id_quantity_discount_rule`)
            ) ENGINE="._MYSQL_ENGINE_." DEFAULT CHARSET=utf8;"
        );

        $result &= $db->execute(
            "CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."quantity_discount_rule_condition_attribute` (
                `id_quantity_discount_rule_condition` int(10) unsigned NOT NULL,
                `id_quantity_discount_rule` int(10) unsigned NOT NULL,
                `id_attribute` int(10) unsigned NOT NULL,
                PRIMARY KEY `primary_ps_quantity_discount_rule_condition_attribute` (`id_quantity_discount_rule_condition`, `id_quantity_discount_rule`, `id_attribute`),
                KEY `id_quantity_discount_rule_condition` (`id_quantity_discount_rule_condition`)
            ) ENGINE="._MYSQL_ENGINE_." DEFAULT CHARSET=utf8;"
        );

        $result &= $db->execute(
            "CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."quantity_discount_rule_condition_feature` (
                `id_quantity_discount_rule_condition` int(10) unsigned NOT NULL,
                `id_quantity_discount_rule` int(10) unsigned NOT NULL,
                `id_feature` int(10) unsigned NOT NULL,
                PRIMARY KEY `primary_ps_quantity_discount_rule_condition_feature` (`id_quantity_discount_rule_condition`, `id_quantity_discount_rule`, `id_feature`),
                KEY `id_quantity_discount_rule_condition` (`id_quantity_discount_rule_condition`)
            ) ENGINE="._MYSQL_ENGINE_." DEFAULT CHARSET=utf8;"
        );

        $result &= $db->execute(
            "CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."quantity_discount_rule_condition_category` (
                `id_quantity_discount_rule_condition` int(10) unsigned NOT NULL,
                `id_quantity_discount_rule` int(10) unsigned NOT NULL,
                `id_category` int(10) unsigned NOT NULL,
                PRIMARY KEY `primary_ps_quantity_discount_rule_condition_category` (`id_quantity_discount_rule_condition`, `id_quantity_discount_rule`, `id_category`),
                KEY `id_quantity_discount_rule_condition` (`id_quantity_discount_rule_condition`)
            ) ENGINE="._MYSQL_ENGINE_." DEFAULT CHARSET=utf8;"
        );

        $result &= $db->execute(
            "CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."quantity_discount_rule_condition_country` (
                `id_quantity_discount_rule_condition` int(10) unsigned NOT NULL,
                `id_quantity_discount_rule` int(10) unsigned NOT NULL,
                `id_country` int(10) unsigned NOT NULL,
                PRIMARY KEY `primary_ps_quantity_discount_rule_condition_country` (`id_quantity_discount_rule_condition`, `id_quantity_discount_rule`, `id_country`),
                KEY `id_quantity_discount_rule_condition` (`id_quantity_discount_rule_condition`)
            ) ENGINE="._MYSQL_ENGINE_." DEFAULT CHARSET=utf8;"
        );

        $result &= $db->execute(
            "CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."quantity_discount_rule_condition_group` (
                `id_quantity_discount_rule_condition` int(10) unsigned NOT NULL,
                `id_quantity_discount_rule` int(10) unsigned NOT NULL,
                `id_group` int(10) unsigned NOT NULL,
                PRIMARY KEY `primary_ps_quantity_discount_rule_condition_group` (`id_quantity_discount_rule_condition`, `id_quantity_discount_rule`, `id_group`),
                INDEX `id_quantity_discount_rule_condition` (`id_quantity_discount_rule_condition`)
            ) ENGINE="._MYSQL_ENGINE_." DEFAULT CHARSET=utf8;"
        );

        $result &= $db->execute(
            "CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."quantity_discount_rule_condition_product` (
                `id_quantity_discount_rule_condition` int(10) unsigned NOT NULL,
                `id_quantity_discount_rule` int(10) unsigned NOT NULL,
                `id_product` int(10) unsigned NOT NULL,
                PRIMARY KEY `primary_ps_quantity_discount_rule_condition_product` (`id_quantity_discount_rule_condition`, `id_quantity_discount_rule`, `id_product`),
                KEY `id_quantity_discount_rule_condition` (`id_quantity_discount_rule_condition`)
            ) ENGINE="._MYSQL_ENGINE_." DEFAULT CHARSET=utf8;"
        );

        $result &= $db->execute(
            "CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."quantity_discount_rule_condition_shop` (
                `id_quantity_discount_rule_condition` int(10) unsigned NOT NULL,
                `id_quantity_discount_rule` int(10) unsigned NOT NULL,
                `id_shop` int(10) unsigned NOT NULL,
                PRIMARY KEY `primary_ps_quantity_discount_rule_condition_shop` (`id_quantity_discount_rule_condition`, `id_quantity_discount_rule`, `id_shop`),
                KEY `id_quantity_discount_rule_condition` (`id_quantity_discount_rule_condition`)
            ) ENGINE="._MYSQL_ENGINE_." DEFAULT CHARSET=utf8;"
        );

        $result &= $db->execute(
            "CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."quantity_discount_rule_condition_zone` (
                `id_quantity_discount_rule_condition` int(10) unsigned NOT NULL,
                `id_quantity_discount_rule` int(10) unsigned NOT NULL,
                `id_zone` int(10) unsigned NOT NULL,
                PRIMARY KEY `primary_ps_quantity_discount_rule_condition_zone` (`id_quantity_discount_rule_condition`, `id_quantity_discount_rule`, `id_zone`),
                KEY `id_quantity_discount_rule_condition` (`id_quantity_discount_rule_condition`)
            ) ENGINE="._MYSQL_ENGINE_." DEFAULT CHARSET=utf8;"
        );

        $result &= $db->execute(
            "CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."quantity_discount_rule_condition_state` (
                `id_quantity_discount_rule_condition` int(10) unsigned NOT NULL,
                `id_quantity_discount_rule` int(10) unsigned NOT NULL,
                `id_state` int(10) unsigned NOT NULL,
                PRIMARY KEY `primary_ps_quantity_discount_rule_condition_state` (`id_quantity_discount_rule_condition`, `id_quantity_discount_rule`, `id_state`),
                KEY `id_quantity_discount_rule_condition` (`id_quantity_discount_rule_condition`)
            ) ENGINE="._MYSQL_ENGINE_." DEFAULT CHARSET=utf8;"
        );

        $result &= $db->execute(
            "CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."quantity_discount_rule_condition_manufacturer` (
                `id_quantity_discount_rule_condition` int(10) unsigned NOT NULL,
                `id_quantity_discount_rule` int(10) unsigned NOT NULL,
                `id_manufacturer` int(10) unsigned NOT NULL,
                PRIMARY KEY `primary_ps_quantity_discount_rule_condition_manufacturer` (`id_quantity_discount_rule_condition`, `id_quantity_discount_rule`, `id_manufacturer`),
                KEY `id_quantity_discount_rule_condition` (`id_quantity_discount_rule_condition`)
            ) ENGINE="._MYSQL_ENGINE_." DEFAULT CHARSET=utf8;"
        );

        $result &= $db->execute(
            "CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."quantity_discount_rule_condition_supplier` (
                `id_quantity_discount_rule_condition` int(10) unsigned NOT NULL,
                `id_quantity_discount_rule` int(10) unsigned NOT NULL,
                `id_supplier` int(10) unsigned NOT NULL,
                PRIMARY KEY `primary_ps_quantity_discount_rule_condition_supplier` (`id_quantity_discount_rule_condition`, `id_quantity_discount_rule`, `id_supplier`),
                KEY `id_quantity_discount_rule_condition` (`id_quantity_discount_rule_condition`)
            ) ENGINE="._MYSQL_ENGINE_." DEFAULT CHARSET=utf8;"
        );

        $result &= $db->execute(
            "CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."quantity_discount_rule_condition_carrier` (
                `id_quantity_discount_rule_condition` int(10) unsigned NOT NULL,
                `id_quantity_discount_rule` int(10) unsigned NOT NULL,
                `id_carrier` int(10) unsigned NOT NULL,
                PRIMARY KEY `primary_ps_quantity_discount_rule_condition_carrier` (`id_quantity_discount_rule_condition`, `id_quantity_discount_rule`, `id_carrier`),
                KEY `id_quantity_discount_rule_condition` (`id_quantity_discount_rule_condition`)
            ) ENGINE="._MYSQL_ENGINE_." DEFAULT CHARSET=utf8;"
        );

        $result &= $db->execute(
            "CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."quantity_discount_rule_condition_gender` (
                `id_quantity_discount_rule_condition` int(10) unsigned NOT NULL,
                `id_quantity_discount_rule` int(10) unsigned NOT NULL,
                `id_gender` int(10) unsigned NOT NULL,
                PRIMARY KEY `primary_ps_quantity_discount_rule_condition_gender` (`id_quantity_discount_rule_condition`, `id_quantity_discount_rule`, `id_gender`),
                KEY `id_quantity_discount_rule_condition` (`id_quantity_discount_rule_condition`)
            ) ENGINE="._MYSQL_ENGINE_." DEFAULT CHARSET=utf8;"
        );

        $result &= $db->execute(
            "CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."quantity_discount_rule_condition_currency` (
                `id_quantity_discount_rule_condition` int(10) unsigned NOT NULL,
                `id_quantity_discount_rule` int(10) unsigned NOT NULL,
                `id_currency` int(10) unsigned NOT NULL,
                PRIMARY KEY `primary_ps_quantity_discount_rule_condition_currency` (`id_quantity_discount_rule_condition`, `id_quantity_discount_rule`, `id_currency`),
                KEY `id_quantity_discount_rule_condition` (`id_quantity_discount_rule_condition`)
            ) ENGINE="._MYSQL_ENGINE_." DEFAULT CHARSET=utf8;"
        );

        $result &= $db->execute(
            "CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."quantity_discount_rule_lang` (
                `id_lang` int(10) NOT NULL,
                `id_quantity_discount_rule` int(10) NOT NULL,
                `name` varchar(255) DEFAULT NULL,
                PRIMARY KEY (`id_lang`,`id_quantity_discount_rule`)
            ) ENGINE="._MYSQL_ENGINE_." DEFAULT CHARSET=utf8;"
        );

        $result &= $db->execute(
            "CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."quantity_discount_rule_message` (
                `id_quantity_discount_rule_message` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `id_quantity_discount_rule` int(10) unsigned NOT NULL,
                `hook_name` varchar(255) NOT NULL,
                PRIMARY KEY `id_quantity_discount_rule_message` (`id_quantity_discount_rule_message`),
                INDEX `id_quantity_discount_rule` (`id_quantity_discount_rule`),
                INDEX `id_quantity_discount_rule_hook_name` (`id_quantity_discount_rule`, `hook_name`)
            ) ENGINE="._MYSQL_ENGINE_." DEFAULT CHARSET=utf8;"
        );

        $result &= $db->execute(
            "CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."quantity_discount_rule_message_lang` (
                `id_quantity_discount_rule_message` int(10) unsigned NOT NULL,
                `id_lang` int(10) unsigned NOT NULL,
                `message` text NOT NULL,
                PRIMARY KEY `id_quantity_discount_rule_message_id_lang` (`id_quantity_discount_rule_message`, `id_lang`)
            ) ENGINE="._MYSQL_ENGINE_." DEFAULT CHARSET=utf8;"
        );


        $result &= $db->execute(
            "CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."quantity_discount_rule_order` (
                `id_order` int(10) unsigned NOT NULL,
                `id_quantity_discount_rule` int(10) unsigned NOT NULL,
                `id_cart_rule` int(10) unsigned NOT NULL,
                PRIMARY KEY (`id_quantity_discount_rule`,`id_cart_rule`),
                KEY `id_order` (`id_order`)
            ) ENGINE="._MYSQL_ENGINE_." DEFAULT CHARSET=utf8;"
        );

        $result &= $db->execute(
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
            ) ENGINE="._MYSQL_ENGINE_." DEFAULT CHARSET=utf8;"
        );

        $result &= $db->execute(
            "CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."quantity_discount_rule_condition_order_state` (
              `id_quantity_discount_rule_condition` int(10) unsigned NOT NULL,
              `id_quantity_discount_rule` int(10) unsigned NOT NULL,
              `id_order_state` int(10) unsigned NOT NULL
            ) ENGINE="._MYSQL_ENGINE_." DEFAULT CHARSET=utf8;"
        );

        return $result;
    }

    public static function dropTables()
    {
        $result = true;

        $db = Db::getInstance();

        $result &= $db->execute("DROP TABLE IF EXISTS `"._DB_PREFIX_."quantity_discount_rule`;");
        $result &= $db->execute("DROP TABLE IF EXISTS `"._DB_PREFIX_."quantity_discount_rule_action`;");
        $result &= $db->execute("DROP TABLE IF EXISTS `"._DB_PREFIX_."quantity_discount_rule_action_attribute`;");
        $result &= $db->execute("DROP TABLE IF EXISTS `"._DB_PREFIX_."quantity_discount_rule_action_category`;");
        $result &= $db->execute("DROP TABLE IF EXISTS `"._DB_PREFIX_."quantity_discount_rule_action_carrier`;");
        $result &= $db->execute("DROP TABLE IF EXISTS `"._DB_PREFIX_."quantity_discount_rule_action_feature`;");
        $result &= $db->execute("DROP TABLE IF EXISTS `"._DB_PREFIX_."quantity_discount_rule_action_product`;");
        $result &= $db->execute("DROP TABLE IF EXISTS `"._DB_PREFIX_."quantity_discount_rule_action_manufacturer`;");
        $result &= $db->execute("DROP TABLE IF EXISTS `"._DB_PREFIX_."quantity_discount_rule_action_supplier`;");
        $result &= $db->execute("DROP TABLE IF EXISTS `"._DB_PREFIX_."quantity_discount_rule_cart`;");
        $result &= $db->execute("DROP TABLE IF EXISTS `"._DB_PREFIX_."quantity_discount_rule_cart_product`;");
        $result &= $db->execute("DROP TABLE IF EXISTS `"._DB_PREFIX_."quantity_discount_rule_condition`;");
        $result &= $db->execute("DROP TABLE IF EXISTS `"._DB_PREFIX_."quantity_discount_rule_condition_attribute`;");
        $result &= $db->execute("DROP TABLE IF EXISTS `"._DB_PREFIX_."quantity_discount_rule_condition_category`;");
        $result &= $db->execute("DROP TABLE IF EXISTS `"._DB_PREFIX_."quantity_discount_rule_condition_carrier`;");
        $result &= $db->execute("DROP TABLE IF EXISTS `"._DB_PREFIX_."quantity_discount_rule_condition_country`;");
        $result &= $db->execute("DROP TABLE IF EXISTS `"._DB_PREFIX_."quantity_discount_rule_condition_currency`;");
        $result &= $db->execute("DROP TABLE IF EXISTS `"._DB_PREFIX_."quantity_discount_rule_condition_feature`;");
        $result &= $db->execute("DROP TABLE IF EXISTS `"._DB_PREFIX_."quantity_discount_rule_condition_gender`;");
        $result &= $db->execute("DROP TABLE IF EXISTS `"._DB_PREFIX_."quantity_discount_rule_condition_group`;");
        $result &= $db->execute("DROP TABLE IF EXISTS `"._DB_PREFIX_."quantity_discount_rule_condition_manufacturer`;");
        $result &= $db->execute("DROP TABLE IF EXISTS `"._DB_PREFIX_."quantity_discount_rule_condition_order_state`;");
        $result &= $db->execute("DROP TABLE IF EXISTS `"._DB_PREFIX_."quantity_discount_rule_condition_product`;");
        $result &= $db->execute("DROP TABLE IF EXISTS `"._DB_PREFIX_."quantity_discount_rule_condition_shop`;");
        $result &= $db->execute("DROP TABLE IF EXISTS `"._DB_PREFIX_."quantity_discount_rule_condition_state`;");
        $result &= $db->execute("DROP TABLE IF EXISTS `"._DB_PREFIX_."quantity_discount_rule_condition_supplier`;");
        $result &= $db->execute("DROP TABLE IF EXISTS `"._DB_PREFIX_."quantity_discount_rule_condition_zone`;");
        $result &= $db->execute("DROP TABLE IF EXISTS `"._DB_PREFIX_."quantity_discount_rule_family`;");
        $result &= $db->execute("DROP TABLE IF EXISTS `"._DB_PREFIX_."quantity_discount_rule_group`;");
        $result &= $db->execute("DROP TABLE IF EXISTS `"._DB_PREFIX_."quantity_discount_rule_lang`;");
        $result &= $db->execute("DROP TABLE IF EXISTS `"._DB_PREFIX_."quantity_discount_rule_message`;");
        $result &= $db->execute("DROP TABLE IF EXISTS `"._DB_PREFIX_."quantity_discount_rule_message_lang`;");
        $result &= $db->execute("DROP TABLE IF EXISTS `"._DB_PREFIX_."quantity_discount_rule_order`;");

        return $result;
    }
}
