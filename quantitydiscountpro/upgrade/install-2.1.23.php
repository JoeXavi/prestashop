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

function upgrade_module_2_1_23()
{
    //Check if column exists
    $columnExists = Db::getInstance()->getRow(
        "SELECT *
        FROM information_schema.COLUMNS
        WHERE
            TABLE_SCHEMA = '"._DB_NAME_."'
        AND TABLE_NAME = '"._DB_PREFIX_."quantity_discount_rule_condition'
        AND COLUMN_NAME = 'schedule';"
    );

    if (!$columnExists) {
        Db::getInstance()->execute(
            "ALTER TABLE `"._DB_PREFIX_."quantity_discount_rule_condition`
                ADD `schedule` text COLLATE 'utf8_general_ci' NULL AFTER `sunday`;"
        );

        $conditions = Db::getInstance()->executeS(
            "SELECT *
            FROM `"._DB_PREFIX_."quantity_discount_rule_condition`;"
        );

        foreach ($conditions as $condition) {
            $schedule = array();

            $dayOn = new stdClass();
            $dayOn->isActive = 1;
            $dayOn->timeFrom = '00:00';
            $dayOn->timeTill = '23:59';

            $dayOff = new stdClass();
            $dayOff->isActive = 0;
            if ((int)$condition['monday']) {
                $schedule[0] = $dayOn;
            } else {
                $schedule[0] = $dayOff;
            }

            if ((int)$condition['tuesday']) {
                $schedule[1] = $dayOn;
            } else {
                $schedule[1] = $dayOff;
            }

            if ((int)$condition['wednesday']) {
                $schedule[2] = $dayOn;
            } else {
                $schedule[2] = $dayOff;
            }

            if ((int)$condition['thursday']) {
                $schedule[3] = $dayOn;
            } else {
                $schedule[3] = $dayOff;
            }

            if ((int)$condition['friday']) {
                $schedule[4] = $dayOn;
            } else {
                $schedule[4] = $dayOff;
            }

            if ((int)$condition['saturday']) {
                $schedule[5] = $dayOn;
            } else {
                $schedule[5] = $dayOff;
            }

            if ((int)$condition['sunday']) {
                $schedule[6] = $dayOn;
            } else {
                $schedule[6] = $dayOff;
            }

            Db::getInstance()->execute(
                "UPDATE `"._DB_PREFIX_."quantity_discount_rule_condition`
                    SET `schedule` = '".Tools::jsonEncode($schedule)."'
                    WHERE `id_quantity_discount_rule_condition` = ".(int)$condition['id_quantity_discount_rule_condition']
            );
        }

        Db::getInstance()->execute(
            "ALTER TABLE `"._DB_PREFIX_."quantity_discount_rule_condition`
                DROP `monday`,
                DROP `tuesday`,
                DROP `wednesday`,
                DROP `thursday`,
                DROP `friday`,
                DROP `saturday`,
                DROP `sunday`;"
        );
    }

    return true;
}
