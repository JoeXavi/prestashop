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

class QuantityDiscountRuleFamily extends ObjectModel
{
    public $id_quantity_discount_rule_family;
    public $name;
    public $active = true;
    public $description;
    public $priority = 0;
    public $execute_other_families = 1;
    public $date_add;
    public $date_upd;

    public static $definition = array(
        'table' => 'quantity_discount_rule_family',
        'primary' => 'id_quantity_discount_rule_family',
        'multilang' => false,
        'fields' => array(
            'active'                    => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'name'                      => array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml', 'required' => true, 'size' => 254),
            'description'               => array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml', 'size' => 65534),
            'priority'                  => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'execute_other_families'    => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'date_add'                  => array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
            'date_upd'                  => array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
        ),
    );

    public static function getQuantityDiscountRuleFamilies($active = true)
    {
        $families = array();

        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->ExecuteS(
            'SELECT qdrf.*
            FROM `'._DB_PREFIX_.'quantity_discount_rule_family` qdrf
            WHERE 1'. ($active ? ' AND qdrf.active = 1' : '').'
            ORDER BY qdrf.priority ASC'
        );

        foreach ($result as $row) {
            $families[$row['id_quantity_discount_rule_family']] = $row;
        }

        return $families;
    }

    public static function getFamilyName($id)
    {
        $qdrf = new QuantityDiscountRuleFamily($id);

        if (Validate::isLoadedObject($qdrf)) {
            return $qdrf->name;
        }

        return false;
    }
}
