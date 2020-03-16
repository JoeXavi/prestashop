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

class QuantityDiscountRuleCondition extends ObjectModel
{
    public $id_quantity_discount_rule_condition;
    public $id_quantity_discount_rule;
    public $id_quantity_discount_rule_group;
    public $id_type;
    public $id_customer;
    public $customer_default_group;
    public $customer_newsletter;
    public $customer_vat;
    public $customer_signedup_date_from;
    public $customer_signedup_date_to;
    public $customer_orders_nb_operator;
    public $customer_orders_nb;
    public $customer_orders_nb_date_from;
    public $customer_orders_nb_date_to;
    public $customer_orders_nb_days;
    public $customer_orders_nb_prod_operator;
    public $customer_orders_nb_prod;
    public $customer_orders_amount_operator;
    public $customer_orders_amount;
    public $customer_orders_amount_currency;
    public $customer_orders_amount_tax = 1;
    public $customer_orders_amount_shipping;
    public $customer_orders_amount_discount;
    public $customer_orders_amount_date_from;
    public $customer_orders_amount_date_to;
    public $customer_orders_amount_days;
    public $customer_orders_amount_orders;
    public $customer_orders_amount_interval;
    public $customer_first_order;
    public $customer_membership_operator;
    public $customer_membership;
    public $customer_birthday;
    public $customer_years_from;
    public $customer_years_to;
    public $cart_amount_operator;
    public $cart_amount;
    public $cart_amount_currency;
    public $cart_amount_tax = 1;
    public $cart_amount_shipping;
    public $cart_amount_discount;
    public $cart_weight_operator;
    public $cart_weight;
    public $products_all_met = 0;
    public $products_operator;
    public $products_amount;
    public $products_amount_currency;
    public $products_amount_tax = 1;
    public $products_nb_operator;
    public $products_nb;
    public $products_nb_same;
    public $products_nb_same_attributes = true;
    public $products_default_category = 1;
    public $products_nb_dif_operator;
    public $products_nb_dif;
    public $products_nb_dif_cat_operator;
    public $products_nb_dif_cat;
    public $product_price_from;
    public $product_price_from_currency;
    public $product_price_from_tax = 1;
    public $product_price_to;
    public $product_price_to_currency;
    public $product_price_to_tax = 1;
    public $product_stock_operator;
    public $product_stock_amount;
    public $apply_discount_to_special = 1;
    public $group_products_by = 'product';
    public $filter_by_product;
    public $filter_by_attribute;
    public $filter_by_feature;
    public $filter_by_category;
    public $filter_by_supplier;
    public $filter_by_manufacturer;
    public $filter_by_price;
    public $filter_by_stock;
    public $schedule;
    public $url_string;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'quantity_discount_rule_condition',
        'primary' => 'id_quantity_discount_rule_condition',
        'fields' => array(
            'id_quantity_discount_rule'         => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true),
            'id_quantity_discount_rule_group'   => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true),
            'id_type'                           => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true),
            'id_customer'                       => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'customer_default_group'            => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'customer_newsletter'               => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'customer_vat'                      => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'customer_signedup_date_from'       => array('type' => self::TYPE_DATE, 'validate' => 'isDateFormat'),
            'customer_signedup_date_to'         => array('type' => self::TYPE_DATE, 'validate' => 'isDateFormat'),
            'customer_orders_nb_operator'       => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'customer_orders_nb'                => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'customer_orders_nb_date_from'      => array('type' => self::TYPE_DATE, 'validate' => 'isDateFormat'),
            'customer_orders_nb_date_to'        => array('type' => self::TYPE_DATE, 'validate' => 'isDateFormat'),
            'customer_orders_nb_days'           => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'customer_orders_nb_prod_operator'  => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'customer_orders_nb_prod'           => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'customer_orders_amount_operator'   => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'customer_orders_amount'            => array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice'),
            'customer_orders_amount_currency'   => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'customer_orders_amount_tax'        => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'customer_orders_amount_shipping'   => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'customer_orders_amount_discount'   => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'customer_orders_amount_date_from'  => array('type' => self::TYPE_DATE, 'validate' => 'isDateFormat'),
            'customer_orders_amount_date_to'    => array('type' => self::TYPE_DATE, 'validate' => 'isDateFormat'),
            'customer_orders_amount_days'       => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'customer_orders_amount_orders'     => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'customer_orders_amount_interval'   => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'customer_first_order'              => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'customer_membership_operator'      => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'customer_membership'               => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'customer_birthday'                 => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'customer_years_from'               => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'customer_years_to'                 => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'cart_amount_operator'              => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'cart_amount'                       => array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice'),
            'cart_amount_currency'              => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'cart_amount_tax'                   => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'cart_amount_shipping'              => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'cart_amount_discount'              => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'cart_weight_operator'              => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'cart_weight'                       => array('type' => self::TYPE_FLOAT, 'validate' => 'isFloat'),
            'products_all_met'                  => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'products_operator'                 => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'products_amount'                   => array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice'),
            'products_amount_currency'          => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'products_amount_tax'               => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'products_nb_operator'              => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'products_nb'                       => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'products_nb_same'                  => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'products_nb_same_attributes'       => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'products_default_category'         => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'products_nb_dif_operator'          => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'products_nb_dif'                   => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'products_nb_dif_cat_operator'      => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'products_nb_dif_cat'               => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'product_price_from'                => array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice'),
            'product_price_from_currency'       => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'product_price_from_tax'            => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'product_price_to'                  => array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice'),
            'product_price_to_currency'         => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'product_price_to_tax'              => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'product_stock_operator'            => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'product_stock_amount'              => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'apply_discount_to_special'         => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'group_products_by'                 => array('type' => self::TYPE_STRING),
            'filter_by_product'                 => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'filter_by_attribute'               => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'filter_by_feature'                 => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'filter_by_category'                => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'filter_by_supplier'                => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'filter_by_manufacturer'            => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'filter_by_price'                   => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'filter_by_stock'                   => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'schedule'                          => array('type' => self::TYPE_STRING),
            'url_string'                        => array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml', 'size' => 254),
        ),
    );

    public function __construct($id = null)
    {
        parent::__construct($id);

        if (Context::getContext()->employee && Context::getContext()->controller instanceof AdminQuantityDiscountRulesController) {
            $this->order_state = $this->getAssociatedRestrictions('order_state', false, true);
            $this->attribute = $this->getAssociatedRestrictions('attribute', false, true);
            if (Feature::isFeatureActive()) {
                $this->feature = $this->getAssociatedRestrictions('feature', false, true);
            }
            $this->category = $this->getAssociatedRestrictions('category', false, true);
            $this->country = $this->getAssociatedRestrictions('country', false, true);
            $this->zone = $this->getAssociatedRestrictions('zone', false, false);
            $this->state = $this->getAssociatedRestrictions('state', false, false);
            if (Group::isFeatureActive()) {
                $this->group = $this->getAssociatedRestrictions('group', false, true);
            }
            if (Shop::isFeatureActive()) {
                $this->shop = $this->getAssociatedRestrictions('shop', false, false);
            }
            $this->carrier = $this->getAssociatedRestrictions('carrier', false, false);
            $this->manufacturer = $this->getAssociatedRestrictions('manufacturer', false, false);
            $this->supplier = $this->getAssociatedRestrictions('supplier', false, false);
            $this->product = $this->getAssociatedRestrictions('product', false, true);
            $this->zone = $this->getAssociatedRestrictions('zone', false, false);
            $this->gender = $this->getAssociatedRestrictions('gender', false, true);
            $this->currency = $this->getAssociatedRestrictions('currency', false, false);

            $this->customer_filter = '';
            if (Validate::isUnsignedId($this->id_customer) &&
                ($customer = new Customer($this->id_customer)) &&
                Validate::isLoadedObject($customer)) {
                $this->customer_filter = $customer->firstname.' '.$customer->lastname.' ('.$customer->email.')';
            }
        }
    }

    public function getId()
    {
        return (int)$this->id;
    }

    /* Return the form for a single cart rule group either with or without product_rules set up */
    public function getNewCondition($condition_group_id = 1, $condition_id = 1)
    {
        $condition = new QuantityDiscountRuleCondition();
        $condition->id_quantity_discount_rule_group = $condition_group_id;
        $condition->id_quantity_discount_rule_condition = $condition_id;

        return $condition;
    }

    /**
     * @param string $type
     * @param bool   $active_only
     * @param bool   $i18n
     * @return array|bool
     * @throws PrestaShopDatabaseException
     */
    public function getAssociatedRestrictions($type, $active_only, $i18n)
    {
        $cache_key = 'QuantityDiscountRuleCondition::getAssociatedRestrictions_'.(int)$this->id.'_'.$type;

        if (!Cache::isStored($cache_key)) {
            $array = array('selected' => array(), 'unselected' => array());

            if (!Validate::isLoadedObject($this)) {
                $sql = 'SELECT '.($type == 'feature' ? 'fv.`id_feature_value` as id_feature' : ($type == 'carrier' ? 't.`id_reference` as id_carrier' : 't.`id_'.$type.'`')).($i18n ? ($type == 'attribute' ? ', CONCAT(agl.`name`, " - ", tl.`name`) as name' : ($type == 'feature' ? ', CONCAT(tl.`name`, " - ", fvl.`value`) as name' : ', tl.name')) : ', t.name').($type == 'product' ? ', reference' : '').', 1 as selected
                    FROM `'._DB_PREFIX_.$type.'` t
                    '.($i18n ? ' LEFT JOIN `'._DB_PREFIX_.$type.'_lang` tl ON (t.id_'.$type.' = tl.id_'.$type.' AND tl.id_lang = '.(int)Context::getContext()->language->id.(in_array($type, array('product', 'category')) ? ' AND id_shop = '.(int)Context::getContext()->shop->id : '').')' : '').
                    ($type == 'attribute' ? ' LEFT JOIN `'._DB_PREFIX_.'attribute_group_lang` agl ON (t.id_attribute_group = agl.id_attribute_group AND agl.id_lang = '.(int)Context::getContext()->language->id.')' : '').
                    ($type == 'feature' ? ' LEFT JOIN `'._DB_PREFIX_.'feature_value` fv ON (tl.id_feature = fv.id_feature) LEFT JOIN `'._DB_PREFIX_.'feature_value_lang` fvl ON (fv.id_feature_value = fvl.id_feature_value AND fvl.id_lang = '.(int)Context::getContext()->language->id.')' : '').'
                    WHERE 1
                    '.($active_only ? 'AND t.active = 1' : '').
                    (in_array($type, array('carrier', 'shop')) ? ' AND t.deleted = 0' : '').
                    ' ORDER BY name ASC';

                $array['unselected'] = Db::getInstance()->executeS($sql);

                if (in_array($type, array('attribute', 'feature', 'manufacturer', 'supplier'))) {
                    array_unshift($array['unselected'], array('id_'.$type => '999999', 'name' => '- Without '.$type.' -', 'selected' => '1'));
                }
            } else {
                $sql = 'SELECT '.($type == 'feature' ? 'fv.`id_feature_value` as id_feature' : ($type == 'carrier' ? 't.`id_reference` as id_carrier' : 't.`id_'.$type.'`')).($i18n ? ($type == 'attribute' ? ', CONCAT(agl.`name`, " - ", tl.`name`) as name' : ($type == 'feature' ? ', CONCAT(tl.`name`, " - ", fvl.`value`) as name' : ', tl.name')) : ', t.name').($type == 'product' ? ', reference' : '').', IF(qdrt.id_'.$type.' IS NULL, 0, 1) as selected
                    FROM `'._DB_PREFIX_.$type.'` t
                    '.($i18n ? 'LEFT JOIN `'._DB_PREFIX_.$type.'_lang` tl ON (t.id_'.$type.' = tl.id_'.$type.' AND tl.id_lang = '.(int)Context::getContext()->language->id.(in_array($type, array('product', 'category')) ? ' AND id_shop = '.(int)Context::getContext()->shop->id : '').')' : '').
                    ($type == 'feature' ? ' LEFT JOIN `'._DB_PREFIX_.'feature_value` fv ON (tl.id_feature = fv.id_feature) LEFT JOIN (SELECT id_'.$type.' FROM `'._DB_PREFIX_.'quantity_discount_rule_condition_'.$type.'` WHERE id_quantity_discount_rule_condition = '.(int)$this->id.') qdrt ON fv.id_'.$type.'_value = qdrt.id_'.$type.' LEFT JOIN `'._DB_PREFIX_.'feature_value_lang` fvl ON (fv.id_feature_value = fvl.id_feature_value AND fvl.id_lang = '.(int)Context::getContext()->language->id.')' : 'LEFT JOIN (SELECT id_'.$type.' FROM `'._DB_PREFIX_.'quantity_discount_rule_condition_'.$type.'` WHERE id_quantity_discount_rule_condition = '.(int)$this->id.') qdrt ON t.id_'.($type == 'carrier' ? 'reference' : $type).' = qdrt.id_'.$type).
                    ($type == 'attribute' ? ' LEFT JOIN `'._DB_PREFIX_.'attribute_group_lang` agl ON (t.id_attribute_group = agl.id_attribute_group AND agl.id_lang = '.(int)Context::getContext()->language->id.')' : '').'
                    WHERE 1 '.($active_only ? ' AND t.active = 1' : '').
                    (in_array($type, array('carrier', 'shop')) ? ' AND t.deleted = 0' : '').
                    ' ORDER BY name ASC';

                $resource = Db::getInstance()->query($sql, false);

                while ($row = Db::getInstance()->nextRow($resource)) {
                    $array[($row['selected']) ? 'selected' : 'unselected'][] = $row;
                }

                if (in_array($type, array('attribute', 'feature', 'manufacturer', 'supplier'))) {
                    $sql = 'SELECT id_'.$type.'
                        FROM `'._DB_PREFIX_.'quantity_discount_rule_condition_'.$type.'`
                        WHERE `id_quantity_discount_rule_condition` = '.(int)$this->id.'
                        AND `id_'.$type.'` = 999999';

                    if (Db::getInstance()->executeS($sql)) {
                        array_unshift($array['selected'], array('id_'.$type => '999999', 'name' => '- Without '.$type.' -', 'selected' => '1'));
                    } else {
                        array_unshift($array['unselected'], array('id_'.$type => '999999', 'name' => '- Without '.$type.' -', 'selected' => '1'));
                    }
                }
            }
            $result = $array;
            Cache::store($cache_key, $result);
        } else {
            $result = Cache::retrieve($cache_key);
        }

        return $result;
    }

    public function getSelectedAssociatedRestrictions($type)
    {
        $cache_key = 'QuantityDiscountRuleCondition::getSelectedAssociatedRestrictions_'.(int)$this->id.'_'.$type;

        if (!Cache::isStored($cache_key)) {
            /* SELECTED ATTRIBUTES */
            $sql = 'SELECT id_'.$type.'
                FROM `'._DB_PREFIX_.'quantity_discount_rule_condition_'.$type.'`
                WHERE `id_quantity_discount_rule_condition` = '.(int)$this->id;

            $result = array();
            $result['selected'] = Db::getInstance()->executeS($sql);

            Cache::store($cache_key, $result);
        } else {
            $result = Cache::retrieve($cache_key);
        }

        return $result;
    }

    protected function getNbProducts()
    {
        $sql = 'SELECT count(*)
                FROM `'._DB_PREFIX_.'product` p
                '.Shop::addSqlAssociation('product', 'p');

        return (int)Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($sql);
    }
}
