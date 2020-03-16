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

include_once(_PS_MODULE_DIR_.'quantitydiscountpro/classes/array_column.php');

class QuantityDiscountRuleMessage extends ObjectModel
{
    public $id_quantity_discount_rule_message;
    public $id_quantity_discount_rule;
    public $hook_name;
    public $message;

    public static $definition = array(
        'table' => 'quantity_discount_rule_message',
        'primary' => 'id_quantity_discount_rule_message',
        'multilang' => true,
        'fields' => array(
            'id_quantity_discount_rule' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true),
            'hook_name'                 => array('type' => self::TYPE_STRING),
            'message'                   => array('type' => self::TYPE_HTML, 'lang' => true, 'size' => 16777216),
        ),
    );
}
