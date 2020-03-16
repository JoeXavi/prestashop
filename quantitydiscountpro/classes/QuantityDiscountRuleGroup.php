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

class QuantityDiscountRuleGroup extends ObjectModel
{
    public $id_quantity_discount_rule_group;
    public $id_quantity_discount_rule;


    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'quantity_discount_rule_group',
        'primary' => 'id_quantity_discount_rule_group',
        'fields' => array(
            'id_quantity_discount_rule' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true),

        ),
    );

    public function getConditions($object = false)
    {
        $result = Db::getInstance()->executeS(
            'SELECT * FROM `'._DB_PREFIX_.'quantity_discount_rule_condition` t
            WHERE `id_quantity_discount_rule_group` = '.(int)$this->id_quantity_discount_rule_group.'
            ORDER BY `id_quantity_discount_rule_condition` ASC'
        );

        if ($object) {
            return ObjectModel::hydrateCollection('QuantityDiscountRuleCondition', $result);
        }

        return $result;
    }
}
