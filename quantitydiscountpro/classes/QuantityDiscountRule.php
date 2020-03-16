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

if (!function_exists('array_column')) {
    include_once(_PS_MODULE_DIR_.'quantitydiscountpro/classes/array_column.php');
}

class QuantityDiscountRule extends ObjectModel
{
    protected static $_discountedProducts = array();

    public $id_quantity_discount_rule;
    public $name;
    public $active = true;
    public $description;
    public $id_family;
    public $code;
    public $code_prefix;
    public $date_from;
    public $date_to;
    public $quantity = 9999;
    public $quantity_per_user = 9999;
    public $priority = 0;
    public $execute_other_rules = 0;
    public $compatible_cart_rules = 0;
    public $compatible_qdp_rules = 1;
    public $apply_products_already_discounted = 1;
    public $modules_exceptions;
    public $highlight;
    public $date_add;
    public $date_upd;

    public static $definition = array(
        'table' => 'quantity_discount_rule',
        'primary' => 'id_quantity_discount_rule',
        'multilang' => true,
        'fields' => array(
            //Information
            'active'                            => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'description'                       => array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml', 'size' => 65534),
            'id_family'                         => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'code'                              => array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml', 'size' => 254),
            'code_prefix'                       => array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml', 'size' => 254),
            'date_from'                         => array('type' => self::TYPE_DATE, 'validate' => 'isDate', 'required' => true),
            'date_to'                           => array('type' => self::TYPE_DATE, 'validate' => 'isDate', 'required' => true),
            'priority'                          => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true),
            'execute_other_rules'               => array('type' => self::TYPE_BOOL, 'validate' => 'isBool', 'required' => true),
            'compatible_cart_rules'             => array('type' => self::TYPE_BOOL, 'validate' => 'isBool', 'required' => true),
            'compatible_qdp_rules'              => array('type' => self::TYPE_BOOL, 'validate' => 'isBool', 'required' => true),
            'apply_products_already_discounted' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool', 'required' => true),
            'quantity'                          => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true),
            'quantity_per_user'                 => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true),
            'modules_exceptions'                => array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml', 'size' => 65534),
            'highlight'                         => array('type' => self::TYPE_BOOL, 'validate' => 'isBool', 'required' => true),
            'date_add'                          => array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
            'date_upd'                          => array('type' => self::TYPE_DATE, 'validate' => 'isDate'),

            //Lang fields
            'name'                              => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isCleanHtml', 'required' => true, 'size' => 254),
        ),
    );

    public function __construct($id = null, $lang = null)
    {
        $this->context = Context::getContext();

        //Default code prefix
        $this->code_prefix = 'QD_';

        parent::__construct($id, $lang);
    }

    public function delete()
    {
        if (!parent::delete()) {
            return false;
        }

        $this->condition_selectors = array('group', 'product', 'category', 'country', 'attribute', 'zone', 'manufacturer', 'carrier', 'supplier', 'order_state', 'shop', 'gender', 'currency');
        $this->action_selectors = array('product', 'category', 'attribute', 'manufacturer', 'supplier');

        $result = Db::getInstance()->delete('quantity_discount_rule_condition', '`id_quantity_discount_rule` = '.(int)$this->id);
        foreach ($this->condition_selectors as $type) {
            $result &= Db::getInstance()->delete('quantity_discount_rule_condition_'.$type, '`id_quantity_discount_rule` = '.(int)$this->id);
        }

        $result &= Db::getInstance()->delete('quantity_discount_rule_action', '`id_quantity_discount_rule` = '.(int)$this->id);
        foreach ($this->action_selectors as $type) {
            $result &= Db::getInstance()->delete('quantity_discount_rule_action_'.$type, '`id_quantity_discount_rule` = '.(int)$this->id);
        }

        $result &= Db::getInstance()->delete('quantity_discount_rule_cart', '`id_quantity_discount_rule` = '.(int)$this->id);
        Db::getInstance()->delete('quantity_discount_rule_message_lang', '`id_quantity_discount_rule_message` IN (SELECT `id_quantity_discount_rule_message` FROM `'._DB_PREFIX_.'quantity_discount_rule_message` WHERE `id_quantity_discount_rule` = '.(int)$this->id.')');

        Db::getInstance()->delete('quantity_discount_rule_message', '`id_quantity_discount_rule` = '.(int)$this->id);
        $result &= Db::getInstance()->delete('quantity_discount_rule_order', '`id_quantity_discount_rule` = '.(int)$this->id);

        return $result;
    }

    public function getGroups($object = false)
    {
        $cache_key = 'QuantityDiscountRule::getGroups_'.(int)$this->id_quantity_discount_rule.'_'.(bool)$object;

        if (!Cache::isStored($cache_key)) {
            $result = Db::getInstance()->executeS(
                'SELECT * FROM `'._DB_PREFIX_.'quantity_discount_rule_group` t
                WHERE `id_quantity_discount_rule` = '.(int)$this->id_quantity_discount_rule.'
                ORDER BY `id_quantity_discount_rule_group` ASC'
            );

            if ($object) {
                $result = ObjectModel::hydrateCollection('QuantityDiscountRuleGroup', $result);
            }

            Cache::store($cache_key, $result);
        } else {
            $result = Cache::retrieve($cache_key);
        }

        return $result;
    }

    public function getActions($object = false)
    {
        $cache_key = 'QuantityDiscountRule::getActions_'.(int)$this->id_quantity_discount_rule.'_'.(bool)$object;

        if (!Cache::isStored($cache_key)) {
            $result = Db::getInstance()->executeS(
                'SELECT * FROM `'._DB_PREFIX_.'quantity_discount_rule_action` t
                WHERE `id_quantity_discount_rule` = '.(int)$this->id_quantity_discount_rule.'
                ORDER BY `id_type` ASC'
            );

            if ($object) {
                foreach ($result as &$row) {
                    $row = new QuantityDiscountRuleAction((int)$row['id_quantity_discount_rule_action']);
                }
            }

            Cache::store($cache_key, $result);
        } else {
            $result = Cache::retrieve($cache_key);
        }

         return $result;
    }

    public function getMessages()
    {
        $cache_key = 'QuantityDiscountRule::getMessages_'.(int)$this->id_quantity_discount_rule;

        if (!Cache::isStored($cache_key)) {
            $sql = 'SELECT * FROM `'._DB_PREFIX_.'quantity_discount_rule_message` t
                WHERE `id_quantity_discount_rule` = '.(int)$this->id_quantity_discount_rule.'
                ORDER BY 1';

            $result = Db::getInstance()->executeS($sql);

            Cache::store($cache_key, $result);
        } else {
            $result = Cache::retrieve($cache_key);
        }

         return $result;
    }

    public function getMessagesByHook($hookName)
    {
        if (!$hookName) {
            return;
        }

        $cache_key = 'QuantityDiscountRule::getMessagesByHook_'.(int)$this->id_quantity_discount_rule.'_'.$hookName;

        if (!Cache::isStored($cache_key)) {
            $sql = 'SELECT * FROM `'._DB_PREFIX_.'quantity_discount_rule_message` t
                WHERE `id_quantity_discount_rule` = '.(int)$this->id_quantity_discount_rule.'
                    AND `hook_name` = \''.pSQL($hookName).'\'';

            $result = Db::getInstance()->executeS($sql);

            Cache::store($cache_key, $result);
        } else {
            $result = Cache::retrieve($cache_key);
        }

         return $result;
    }

    public static function getQuantityDiscountRulesByFamily($id_family = false, $codes = false, $highlight = false)
    {
        if ($id_family && !(int)$id_family > 0) {
            return;
        }

        $cache_key = 'QuantityDiscountRule::getQuantityDiscountRulesByFamily_'.($id_family ? (int)$id_family : 'false').'_'.md5(Tools::jsonEncode($codes));

        if (!Cache::isStored($cache_key)) {
            $sql = "SELECT * FROM `"._DB_PREFIX_ ."quantity_discount_rule` cp
                LEFT JOIN `"._DB_PREFIX_."quantity_discount_rule_lang` cpl
                    ON (cp.`id_quantity_discount_rule` = cpl.`id_quantity_discount_rule` AND cpl.`id_lang` = ".(int)Context::getContext()->cart->id_lang.")
                WHERE cp.`active` = 1".
                ($id_family ? " AND `id_family` = ".(int)$id_family : "").
                ($codes ? " AND (`code` = '' OR code IN ('".implode('\',\'', $codes)."'))" : '').
                ($highlight ? " AND `code` != '' AND `highlight` = 1" : '').
                (!$codes && !$highlight ? " AND `code` = ''" : '').
                " ORDER BY cp.`priority` ASC, cp.`id_quantity_discount_rule` ASC";

            $result = Db::getInstance()->ExecuteS($sql);

            Cache::store($cache_key, $result);
        } else {
            $result = Cache::retrieve($cache_key);
        }

        return $result;
    }

    public static function getQuantityDiscountRulesWithCondition($id_type)
    {
        $sql = "SELECT qdr.`id_quantity_discount_rule`, qdrc.`id_quantity_discount_rule_condition`
            FROM `"._DB_PREFIX_ ."quantity_discount_rule` qdr
            INNER JOIN  `"._DB_PREFIX_ ."quantity_discount_rule_condition` qdrc ON (qdr.`id_quantity_discount_rule` = qdrc.`id_quantity_discount_rule`)
            WHERE qdrc.`id_type` = ".(int)$id_type;

        return Db::getInstance()->ExecuteS($sql);
    }

    public static function getQuantityDiscountRulesByFamilyForMessages($id_family, $hookName)
    {
        if (!(int)$id_family > 0) {
            return;
        }

        $cache_key = (int)$id_family.'_'.$hookName;

        if (!Cache::isStored($cache_key)) {
            $sql = "SELECT qdr.`id_quantity_discount_rule`
                FROM `"._DB_PREFIX_ ."quantity_discount_rule` qdr
                INNER JOIN `"._DB_PREFIX_."quantity_discount_rule_lang` qdrl
                    ON (qdr.`id_quantity_discount_rule` = qdrl.`id_quantity_discount_rule` AND qdrl.`id_lang` = ".(int)Context::getContext()->cart->id_lang.")
                INNER JOIN `"._DB_PREFIX_."quantity_discount_rule_message` qdrm
                    ON (qdrm.`id_quantity_discount_rule` = qdr.`id_quantity_discount_rule` AND qdrm.`hook_name` = '".$hookName."')
                INNER JOIN `"._DB_PREFIX_."quantity_discount_rule_message_lang` qdrml
                    ON (qdrm.`id_quantity_discount_rule_message` = qdrml.`id_quantity_discount_rule_message` AND qdrml.`id_lang` = ".(int)Context::getContext()->cart->id_lang.")
                WHERE qdr.`active` = 1
                    AND `id_family` = ".(int)$id_family."
                ORDER BY qdr.`priority` ASC, qdr.`id_quantity_discount_rule` ASC";

            $result = Db::getInstance()->ExecuteS($sql);

            Cache::store($cache_key, $result);
        } else {
            $result = Cache::retrieve($cache_key);
        }

        return $result;
    }

    public static function getNbProductsOrder($id)
    {
        return Db::getInstance()->getValue(
            'SELECT SUM(`product_quantity`)
            FROM `'._DB_PREFIX_.'order_detail`
            WHERE `id_order` = '.(int)$id
        );
    }

    public function getMessagesToDisplay($hookName)
    {
        $messages = array();
        foreach ($this->getMessagesByHook($hookName) as $message) {
            $messages[] = $message;
        };

        return $messages;
    }

    public function getHighlightedQuantityDiscountRules()
    {
        $context = Context::getContext();

        if (!Validate::isLoadedObject($context->cart)) {
            return array();
        }
        /* If no products, return. We don't do this before because we have to remove cart rules as PS does */
        if (!$context->cart->nbProducts()) {
            return array();
        }

        //Get all rules and check if any of them should be applied
        $quantityDiscountRulesHighlight = array();
        foreach (QuantityDiscountRuleFamily::getQuantityDiscountRuleFamilies() as $ruleFamily) {
            $quantityDiscountRules = $this->getQuantityDiscountRulesByFamily($ruleFamily['id_quantity_discount_rule_family'], false, true);


            if (is_array($quantityDiscountRules) && count($quantityDiscountRules)) {
                foreach ($quantityDiscountRules as $quantityDiscountRule) {
                    $quantityDiscountRuleObj = new QuantityDiscountRule((int)$quantityDiscountRule['id_quantity_discount_rule']);

                    if (!$quantityDiscountRuleObj->isQuantityDiscountRuleValid(null, true)) {
                        continue;
                    }

                    if (!$quantityDiscountRuleObj->compatibleCartRules()) {
                        continue;
                    }

                    if (!$quantityDiscountRuleObj->validateQuantityDiscountRuleConditions()) {
                        continue;
                    }

                    $quantityDiscountRule['id_cart_rule'] = $this->getIdCartRuleFromQuantityDiscountRuleFromThisCart((int)$quantityDiscountRule['id_quantity_discount_rule'], $context->cart->id);
                    $quantityDiscountRulesHighlight[] = $quantityDiscountRule;
                }
            }
        }

        return $quantityDiscountRulesHighlight;
    }


    public function createAndRemoveRules($code = null, $context = null)
    {
        if (!$context) {
            $context = Context::getContext();
        }

        if (!Validate::isLoadedObject($context->cart)) {
            return;
        }

        $file = fopen(dirname(__FILE__).'/../sessions/lock_'.$context->cart->id, 'w+');
        if (!is_writable(dirname(__FILE__).'/../sessions/')) {
            die(dirname(__FILE__).'/../sessions/'.' must be writable!');
        }

        // exclusive lock
        if (flock($file, LOCK_EX | LOCK_NB)) {
            self::$_discountedProducts = array();

            /*** PERFORMANCE ***/
            /*
            $cache_key = 'QuantityDiscountRule::createAndRemoveRules_'.(int)$context->cart->id.'_'.(int)Cart::getNbProducts($context->cart->id).'_'.$context->cart->getOrderTotal().'_'.(int)$context->cart->id_address_delivery.'_'.(int)$context->cart->getNbOfPackages().'_'.$context->cart->getTotalWeight().'_'.md5(serialize($context->cart->getProducts()));

            if (Cache::isStored($cache_key)) {
                // release lock
                flock($file, LOCK_UN);
                fclose($file);

                return false;
            }

            Cache::store($cache_key, true);
            */

            /* Avoid recursion */
            $backtrace = version_compare(PHP_VERSION, '5.3.6', '>=') ? debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS) : debug_backtrace();

            if (in_array('removeQuantityDiscountCartRule', array_column($backtrace, 'function'))
                || in_array('loadCarrier', array_column($backtrace, 'function'))
                || in_array('loadCarriers', array_column($backtrace, 'function'))
                || in_array('updateCarrier', array_column($backtrace, 'function'))
                || in_array('_setCarrierSelection', array_column($backtrace, 'function'))
                || in_array('getCartRules', array_column($backtrace, 'function')) //PS 1.7.4
                || in_array('OrderFees', array_column($backtrace, 'class'))
                //|| (Tools::getValue('method') == 'updateCarrierAndGetPayments') // Executed when carrier is changed
            ) {
                // release lock
                flock($file, LOCK_UN);
                fclose($file);

                return false;
            }

            foreach (array('carriercompare', 'productadditionalfeatures', 'attributegrid', 'imaximprimepedidosservidor', 'appagebuilder') as $modules_exception) {
                foreach (array_column($backtrace, 'file') as $element) {
                    if (strpos($element, $modules_exception) !== false) {
                        // release lock
                        flock($file, LOCK_UN);
                        fclose($file);

                        return false;
                    }
                }
            }

            //Kept the discount codes to apply again the rule
            $cartRulesCodes = array();
            if ($code = Tools::strtoupper($code)) {
                $cartRulesCodes[] = $code;
            }

            //Remove all quantity discount rules from current cart
            $quantityDiscountRulesAtCart = self::getQuantityDiscountRulesAtCart((int)$context->cart->id);
            $cartRulesRemoved = false;
            if (is_array($quantityDiscountRulesAtCart) && count($quantityDiscountRulesAtCart)) {
                $cartRulesRemoved = true;
                foreach ($quantityDiscountRulesAtCart as $quantityDiscountRuleAtCart) {
                    //We save the discount code to apply it after
                    if ($quantityDiscountRuleAtCart['code']) {
                        $cartRulesCodes[] = $quantityDiscountRuleAtCart['code'];
                    }
                    //d($backtrace);
                    self::removeQuantityDiscountCartRule($quantityDiscountRuleAtCart['id_cart_rule'], (int)$context->cart->id);
                }
            }
            $cartRulesCodes = array_unique($cartRulesCodes);

            /* If no products, return. We don't do this before because we have to remove cart rules as PS does */
            if (!$context->cart->nbProducts()) {
                // release lock
                flock($file, LOCK_UN);
                fclose($file);

                return false;
            }

            //Get all rules and check if any of them should be applied
            $cartRulesCreated = false;
            foreach (QuantityDiscountRuleFamily::getQuantityDiscountRuleFamilies() as $ruleFamily) {
                $cartRulesCreatedSameFamily = false;
                $quantityDiscountRules = $this->getQuantityDiscountRulesByFamily($ruleFamily['id_quantity_discount_rule_family'], $cartRulesCodes);
                if (is_array($quantityDiscountRules) && count($quantityDiscountRules)) {
                    foreach ($quantityDiscountRules as $quantityDiscountRule) {
                        $quantityDiscountRuleObj = new QuantityDiscountRule((int)$quantityDiscountRule['id_quantity_discount_rule']);

                        //If there a rule created and current rule is not compatible with others
                        if ($cartRulesCreated && !$quantityDiscountRuleObj->compatible_qdp_rules) {
                            continue;
                        }

                        if (!$quantityDiscountRuleObj->isQuantityDiscountRuleValid($cartRulesCodes)) {
                            continue;
                        }

                        if (!$quantityDiscountRuleObj->compatibleCartRules()) {
                            continue;
                        }

                        if (!$quantityDiscountRuleObj->validateQuantityDiscountRuleConditions()) {
                            continue;
                        }

                        if ($this->calculateCartRule($quantityDiscountRuleObj)) {
                            $cartRulesCreated = true;
                            $cartRulesCreatedSameFamily = true;
                            if (!$quantityDiscountRuleObj->execute_other_rules) {
                                break;
                            }
                        }
                    }
                }

                if ($cartRulesCreatedSameFamily && !$ruleFamily['execute_other_families']) {
                    break;
                }
            }

            if ($cartRulesRemoved || $cartRulesCreated) {
                // release lock
                flock($file, LOCK_UN);
                fclose($file);

                return true;
                //die(Tools::jsonEncode(array('refresh' => true)));
            }

            if ($code) {
                // release lock
                flock($file, LOCK_UN);
                fclose($file);

                return Tools::displayError('You cannot use this voucher');
            }

            // release lock
            flock($file, LOCK_UN);
        }

        fclose($file);

        return false;
    }

    public function calculateCartRule($quantityDiscountRule)
    {
        $cartProducts = $this->context->cart->getProducts();
        $taxCalculationMethod = Group::getPriceDisplayMethod(Group::getCurrent()->id);

        $actions = $quantityDiscountRule->getActions(true);

        /* initialize vars */
        /* As for some actions can be more than 1, it cannot be defined inside the foreach */

        $minCoincidences = PHP_INT_MAX;
        $tempCartRule = array();
        $actionsBuyX = array();
        $cartProductsFilteredBuyX = array();
        $this->context->cookie->qdp_shipping_cost = null;

        $reductionAmount = 0;
        $tempCartRuleCounter = 0;

        foreach ($actions as $action) {
            switch ((int)$action->id_type) {
                /**
                 *
                 * Shipping cost - Fixed discount
                 *
                 */
                case 1:
                    if (!$action->reduction_amount) {
                        break;
                    }

                    $tempCartRule[$tempCartRuleCounter]['reduction_amount'] = 0;
                    $tempCartRule[$tempCartRuleCounter]['reduction_currency'] = (int)$action->reduction_currency;
                    $tempCartRule[$tempCartRuleCounter]['reduction_tax'] = (int)$action->reduction_tax;
                    $shippingCostsByCarrier = array();

                    if (Tools::getValue('delivery_option')) {
                        $this->context->cart->setDeliveryOption(Tools::getValue('delivery_option'));
                    }

                    $carrierList = $this->context->cart->getDeliveryOption();
                    $carrierList = explode(',', rtrim(reset($carrierList), ','));

                    foreach ($carrierList as $carrier) {
                        $carrier = new Carrier($carrier);

                        if ($action->filter_by_carrier) {
                            $restrictionCarriers = $action->getSelectedAssociatedRestrictions('carrier');
                            if (!in_array((int)$carrier->id_reference, array_column($restrictionCarriers['selected'], 'id_carrier'))) {
                                continue;
                            }
                        }

                        if (count($carrierList) > 1) {
                            if ($this->context->cart->id_address_delivery) {
                                $address = new Address($this->context->cart->id_address_delivery);
                                $country = new Country($address->id_country);
                            } else {
                                $country = null;
                            }

                            $package_list = $this->context->cart->getPackageList();
                            // Foreach addresses
                            foreach ($package_list as $packages) {
                                foreach ($packages as $package) {
                                    foreach ($package['carrier_list'] as $id_carrier) {
                                        $shippingCost = self::convertPriceFull($this->context->cart->getPackageShippingCost((int)$carrier->id, (int)$action->reduction_tax, $country, $package['product_list']), $this->context->currency, new Currency((int)$action->reduction_currency));
                                    }
                                }
                            }
                        } else {
                            $shippingCost = self::convertPriceFull($this->context->cart->getTotalShippingCost(null, (int)$action->reduction_percent_tax), $this->context->currency, new Currency((int)$action->reduction_currency));
                        }

                        $orderTotal = $this->context->cart->getOrderTotal((int)$action->reduction_tax, Cart::ONLY_PRODUCTS);
                        $orderTotal -= $this->getGiftProductsValue((int)$action->reduction_tax);
                        $orderTotal = self::convertPriceFull($orderTotal, $this->context->currency, new Currency((int)$action->reduction_currency));

                        if ($orderTotal >= $shippingCost) {
                            // Product amount is higher than shipping cost, we can apply a rule
                            if ($shippingCost <= $action->reduction_amount) {
                                $tempCartRule[$tempCartRuleCounter]['reduction_amount'] += $shippingCost;
                            } else {
                                $tempCartRule[$tempCartRuleCounter]['reduction_amount'] += $action->reduction_amount;
                            }
                        } else {
                            // Product amount is lower than shipping cost, we have to modify shipping cost (check Cart getPackageShippingCost())
                            $shippingCostsByCarrier[$carrier->id] = self::convertPriceFull($action->reduction_amount, new Currency((int)$action->reduction_currency), $this->context->currency);
                        }
                    }

                    if (!$tempCartRule[$tempCartRuleCounter]['reduction_amount']) {
                        unset($tempCartRule[$tempCartRuleCounter]);
                    }

                    $this->context->cookie->qdp_shipping_cost = Tools::jsonEncode($shippingCostsByCarrier);

                    break;

                /**
                 *
                 * Shipping cost - Percentage discount
                 *
                 */
                case 5:
                    if (!$action->reduction_percent) {
                        break;
                    }

                    $tempCartRule[$tempCartRuleCounter]['reduction_amount'] = 0;
                    $tempCartRule[$tempCartRuleCounter]['reduction_currency'] = (int)$action->reduction_currency;
                    $tempCartRule[$tempCartRuleCounter]['reduction_tax'] = (int)$action->reduction_tax;
                    $shippingCostsByCarrier = array();

                    if (Tools::getValue('delivery_option')) {
                        $this->context->cart->setDeliveryOption(Tools::getValue('delivery_option'));
                    }

                    $carrierList = $this->context->cart->getDeliveryOption();
                    $carrierList = explode(',', rtrim(reset($carrierList), ','));

                    foreach ($carrierList as $carrier) {
                        $carrier = new Carrier($carrier);

                        if ($action->filter_by_carrier) {
                            $restrictionCarriers = $action->getSelectedAssociatedRestrictions('carrier');
                            if (!in_array((int)$carrier->id_reference, array_column($restrictionCarriers['selected'], 'id_carrier'))) {
                                continue;
                            }
                        }

                        if (count($carrierList) > 1) {
                            if ($this->context->cart->id_address_delivery) {
                                $address = new Address($this->context->cart->id_address_delivery);
                                $country = new Country($address->id_country);
                            } else {
                                $country = null;
                            }

                            $package_list = $this->context->cart->getPackageList();
                            // Foreach addresses
                            foreach ($package_list as $id_address => $packages) {
                                foreach ($packages as $id_package => $package) {
                                    foreach ($package['carrier_list'] as $id_carrier) {
                                        $shippingCost = self::convertPriceFull(($this->context->cart->getPackageShippingCost((int)$carrier->id, (int)$action->reduction_tax, $country, $package['product_list'])*$action->reduction_percent)/100, $this->context->currency, new Currency((int)$action->reduction_currency));
                                    }
                                }
                            }
                        } else {
                            $shippingCost = self::convertPriceFull(($this->context->cart->getTotalShippingCost(null, (int)$action->reduction_percent_tax)*$action->reduction_percent)/100, $this->context->currency, new Currency((int)$action->reduction_currency));
                        }

                        $orderTotal = $this->context->cart->getOrderTotal((int)$action->reduction_tax, Cart::ONLY_PRODUCTS);
                        $orderTotal -= $this->getGiftProductsValue((int)$action->reduction_tax);
                        $orderTotal = self::convertPriceFull($orderTotal, $this->context->currency, new Currency((int)$action->reduction_currency));

                        $reductionAmount = (($action->reduction_max_amount > 0 && $shippingCost > $action->reduction_max_amount) ? $action->reduction_max_amount : $shippingCost);

                        if ($orderTotal >= $shippingCost) {
                            // Product amount is higher than shipping cost, we can apply a rule
                            $tempCartRule[$tempCartRuleCounter]['reduction_amount'] += $reductionAmount;
                        } else {
                            // Product amount is lower than shipping cost, we have to modify shipping cost (check Cart getPackageShippingCost())
                            $shippingCostsByCarrier[$carrier->id] = $reductionAmount;
                        }
                    }

                    if (!$reductionAmount) {
                        unset($tempCartRule[$tempCartRuleCounter]);
                    }

                    $this->context->cookie->qdp_shipping_cost = Tools::jsonEncode($shippingCostsByCarrier);

                    break;

                /**
                 *
                 * Order amount - Fixed discount
                 *
                 */
                case 2:
                    if (!$action->reduction_amount) {
                        break;
                    }

                    $shippingCost = $this->context->cart->getTotalShippingCost(null, (int)$action->reduction_tax);
                    $orderTotal = $this->context->cart->getOrderTotal((int)$action->reduction_tax, Cart::ONLY_PRODUCTS);
                    $orderTotal -= $this->getGiftProductsValue((int)$action->reduction_tax);

                    /**
                     *
                     * Check if shipping is included in the amount to discount,
                     * because is possible that the amount to discount is higher that the product amount,
                     * so we need to know if we have to reduce only products or products + shipping
                     *
                     */
                    if ((int)$action->reduction_shipping) {
                        if ($orderTotal < $action->reduction_amount && $shippingCost < $action->reduction_amount) {
                            $tempCartRule[$tempCartRuleCounter]['free_shipping'] = 1;
                            $maxDiscount = $action->reduction_amount - $shippingCost;
                        } else {
                            $maxDiscount = $orderTotal;
                        }
                    } else {
                        $maxDiscount = $orderTotal;
                    }


                    if ($maxDiscount) {
                        $maxDiscount = self::convertPriceFull($maxDiscount, $this->context->currency, new Currency((int)$action->reduction_currency), false);

                        $tempCartRule[$tempCartRuleCounter]['reduction_amount'] = ($maxDiscount > $action->reduction_amount) ? $action->reduction_amount : $maxDiscount;
                        $tempCartRule[$tempCartRuleCounter]['reduction_currency'] = (int)$action->reduction_currency;
                        $tempCartRule[$tempCartRuleCounter]['reduction_tax'] = (int)$action->reduction_tax;
                    }

                    break;

                /**
                 *
                 * Order amount - Percentage discount
                 *
                 */
                case 3:
                    $orderTotal = $this->context->cart->getOrderTotal((int)$action->reduction_tax, Cart::ONLY_PRODUCTS);
                    $orderTotal -= $this->getGiftProductsValue((int)$action->reduction_percent_tax);

                    /**
                     *
                     * Check if shipping is included in the amount to discount,
                     * because is possible that the amount to discount is higher that the product amount,
                     * so we need to know if we have to reduce only products or products + shipping
                     *
                     */
                    if ((int)$action->reduction_percent_shipping) {
                        $shippingCost = $this->context->cart->getTotalShippingCost(null, (int)$action->reduction_percent_tax);
                        $totalAmount = $orderTotal + $shippingCost;
                    } else {
                        $shippingCost = 0;
                        $totalAmount = $orderTotal;
                    }

                    /** Remove discounts */
                    if (!$action->reduction_percent_discount) {
                        $totalAmount -= ($this->context->cart->getOrderTotal((int)$action->reduction_percent_tax, Cart::ONLY_DISCOUNTS) - $this->getGiftProductsValue((int)$action->reduction_percent_tax));
                    }

                    if ($totalAmount) {
                        $totalAmount = self::convertPriceFull(($totalAmount*$action->reduction_percent)/100, $this->context->currency, new Currency((int)$action->reduction_currency), false);

                        $orderTotalConverted = self::convertPriceFull($orderTotal, $this->context->currency, new Currency((int)$action->reduction_currency), false);
                        $shippingCostConverted = self::convertPriceFull($shippingCost, $this->context->currency, new Currency((int)$action->reduction_currency), false);

                        if ($totalAmount > ($orderTotalConverted - $shippingCostConverted)) {
                            $tempCartRule[$tempCartRuleCounter]['free_shipping'] = 1;
                            $totalAmount = $totalAmount - $shippingCostConverted;
                        }

                        $tempCartRule[$tempCartRuleCounter]['reduction_amount'] = (($action->reduction_max_amount > 0 && $totalAmount > $action->reduction_max_amount) ? $action->reduction_max_amount : $totalAmount);
                        $tempCartRule[$tempCartRuleCounter]['reduction_currency'] = (int)$action->reduction_currency;
                        $tempCartRule[$tempCartRuleCounter]['reduction_tax'] = (int)$action->reduction_percent_tax;
                    }

                    break;

                /**
                 *
                 * Buy X - Get Y with fixed discount
                 *
                 */
                case 6:
                    $cartProductsFiltered = $quantityDiscountRule->filterProducts($cartProducts, $action);

                    if (!$cartProductsFiltered) {
                        continue;
                    }

                    if (!$productsGrouped = $this->groupProducts($this->context->cart->id, $cartProductsFiltered, $action)) {
                        continue;
                    }

                    foreach ($productsGrouped as &$productGrouped) {
                        uasort($productGrouped['products'], $this->makeComparer(array($taxCalculationMethod ? 'price_with_reduction_without_tax' : 'price_with_reduction', $action->apply_discount_sort == 'cheapest' ? SORT_ASC : SORT_DESC)));
                        unset($productGrouped);
                    }

                    foreach ($productsGrouped as $productGrouped) {
                        if ($this->compareValue(0, (int)$productGrouped['cart_quantity'], (int)$action->products_nb_each)) {
                            $remainingTimesToApplyPromo = (($action->nb_repetitions == 'custom') ? min((int)($productGrouped['cart_quantity']/(int)$action->products_nb_each), (int)$action->nb_repetitions_custom) : (int)($productGrouped['cart_quantity']/(int)$action->products_nb_each))*(int)$action->apply_discount_to_nb;

                            $quantityDiscountRule->addDiscountedProducts((int)$action->id_type, $productGrouped['products'], $remainingTimesToApplyPromo*$action->products_nb_each);

                            foreach ($productGrouped['products'] as $product) {
                                $productPrice = $this->getDiscountedAmount($action, $product, false);

                                $timesToApplyPromoInThisProduct = min($remainingTimesToApplyPromo, (int)$product['cart_quantity'], (($action->apply_discount_to_stock && Configuration::get('PS_STOCK_MANAGEMENT')) ? (int)$product['quantity_available'] : PHP_INT_MAX));

                                $productPrice = self::convertPriceFull($productPrice, $this->context->currency, new Currency((int)$action->reduction_currency), false);

                                if ($productPrice > $action->reduction_amount) {
                                    $reductionAmount += $action->reduction_amount*$timesToApplyPromoInThisProduct;
                                } else {
                                    $reductionAmount += $productPrice*$timesToApplyPromoInThisProduct;
                                }

                                $remainingTimesToApplyPromo -= $timesToApplyPromoInThisProduct;
                                if (!$remainingTimesToApplyPromo) {
                                    break;
                                }
                            }
                        }
                    }

                    if ($reductionAmount > 0) {
                        $tempCartRule[$tempCartRuleCounter]['reduction_amount'] = (($action->reduction_max_amount > 0 && $reductionAmount > $action->reduction_max_amount) ? $action->reduction_max_amount : $reductionAmount);
                        $tempCartRule[$tempCartRuleCounter]['reduction_currency'] = (int)$action->reduction_currency;
                        $tempCartRule[$tempCartRuleCounter]['reduction_tax'] = (int)$action->reduction_tax;
                    }

                    break;

                /**
                 *
                 * Buy X - Get Y with percentage discount
                 *
                 */
                case 7:
                    $cartProductsFiltered = $quantityDiscountRule->filterProducts($cartProducts, $action);

                    if (!$cartProductsFiltered) {
                        continue;
                    }

                    if (!$productsGrouped = $this->groupProducts($this->context->cart->id, $cartProductsFiltered, $action)) {
                        continue;
                    }

                    foreach ($productsGrouped as &$productGrouped) {
                        uasort($productGrouped['products'], $this->makeComparer(array($taxCalculationMethod ? 'price_with_reduction_without_tax' : 'price_with_reduction', $action->apply_discount_sort == 'cheapest' ? SORT_ASC : SORT_DESC)));
                        unset($productGrouped);
                    }

                    foreach ($productsGrouped as $productGrouped) {
                        if ($this->compareValue(0, (int)$productGrouped['cart_quantity'], (int)$action->products_nb_each)) {
                            $remainingTimesToApplyPromo = (($action->nb_repetitions == 'custom') ? min((int)($productGrouped['cart_quantity']/(int)$action->products_nb_each), (int)$action->nb_repetitions_custom) : (int)($productGrouped['cart_quantity']/(int)$action->products_nb_each))*(int)$action->apply_discount_to_nb;

                            $quantityDiscountRule->addDiscountedProducts((int)$action->id_type, $productGrouped['products'], $remainingTimesToApplyPromo*$action->products_nb_each);

                            foreach ($productGrouped['products'] as $product) {
                                $unitDiscount = $this->getDiscountedAmount($action, $product, true);

                                $unitDiscountConverted = self::convertPriceFull($unitDiscount, $this->context->currency, new Currency((int)$action->reduction_currency), false);
                                $reductionProductMaxAmountConverted = self::convertPriceFull($action->reduction_product_max_amount, $this->context->currency, new Currency((int)$action->reduction_currency), false);

                                $unitDiscount = (($reductionProductMaxAmountConverted > 0 && $unitDiscountConverted > $reductionProductMaxAmountConverted) ? $reductionProductMaxAmountConverted : $unitDiscountConverted);

                                $timesToApplyPromoInThisProduct = min($remainingTimesToApplyPromo, (int)$product['cart_quantity'], (($action->apply_discount_to_stock && Configuration::get('PS_STOCK_MANAGEMENT')) ? (int)$product['quantity_available'] : PHP_INT_MAX));

                                $reductionAmount += $unitDiscount*$timesToApplyPromoInThisProduct;

                                $remainingTimesToApplyPromo -= $timesToApplyPromoInThisProduct;
                                if (!$remainingTimesToApplyPromo) {
                                    break;
                                }
                            }
                        }
                    }

                    if ($reductionAmount > 0) {
                        $tempCartRule[$tempCartRuleCounter]['reduction_amount'] = (($action->reduction_max_amount > 0 && $reductionAmount > $action->reduction_max_amount) ? $action->reduction_max_amount : $reductionAmount);
                        $tempCartRule[$tempCartRuleCounter]['reduction_currency'] = (int)$action->reduction_currency;
                        $tempCartRule[$tempCartRuleCounter]['reduction_tax'] = (int)$action->reduction_percent_tax;
                    }

                    break;

                /**
                 *
                 * Buy X - Get Y with fixed price
                 *
                 */
                case 8:
                    $cartProductsFiltered = $quantityDiscountRule->filterProducts($cartProducts, $action);

                    if (!$cartProductsFiltered) {
                        continue;
                    }

                    if (!$productsGrouped = $this->groupProducts($this->context->cart->id, $cartProductsFiltered, $action)) {
                        continue;
                    }

                    foreach ($productsGrouped as &$productGrouped) {
                        uasort($productGrouped['products'], $this->makeComparer(array($taxCalculationMethod ? 'price_with_reduction_without_tax' : 'price_with_reduction', $action->apply_discount_sort == 'cheapest' ? SORT_ASC : SORT_DESC)));
                        unset($productGrouped);
                    }

                    foreach ($productsGrouped as $productGrouped) {
                        if ($this->compareValue(0, (int)$productGrouped['cart_quantity'], (int)$action->products_nb_each)) {
                            $remainingTimesToApplyPromo = (($action->nb_repetitions == 'custom') ? min((int)($productGrouped['cart_quantity']/(int)$action->products_nb_each), (int)$action->nb_repetitions_custom) : (int)($productGrouped['cart_quantity']/(int)$action->products_nb_each))*(int)$action->apply_discount_to_nb;

                            $quantityDiscountRule->addDiscountedProducts((int)$action->id_type, $productGrouped['products'], $remainingTimesToApplyPromo*$action->products_nb_each);

                            foreach ($productGrouped['products'] as $product) {
                                $productPrice = $this->getDiscountedAmount($action, $product, false);

                                $timesToApplyPromoInThisProduct = min($remainingTimesToApplyPromo, (int)$product['cart_quantity'], (($action->apply_discount_to_stock && Configuration::get('PS_STOCK_MANAGEMENT')) ? (int)$product['quantity_available'] : PHP_INT_MAX));

                                $productPrice = self::convertPriceFull($productPrice, $this->context->currency, new Currency((int)$action->reduction_currency), false);

                                $reductionAmount += ($productPrice-$action->reduction_amount)*$timesToApplyPromoInThisProduct;

                                $remainingTimesToApplyPromo -= $timesToApplyPromoInThisProduct;
                                if (!$remainingTimesToApplyPromo) {
                                    break;
                                }
                            }
                        }
                    }

                    if ($reductionAmount > 0) {
                        $tempCartRule[$tempCartRuleCounter]['reduction_amount'] = (($action->reduction_max_amount > 0 && $reductionAmount > $action->reduction_max_amount) ? $action->reduction_max_amount : $reductionAmount);
                        $tempCartRule[$tempCartRuleCounter]['reduction_currency'] = (int)$action->reduction_currency;
                        $tempCartRule[$tempCartRuleCounter]['reduction_tax'] = (int)$action->reduction_tax;
                    }

                    break;

                /**
                 *
                 * Buy X - Get Y - Gift product (by product)
                 *
                 */
                case 31:
                    $cartProductsFiltered = $quantityDiscountRule->filterProducts($cartProducts, $action);

                    if (!$cartProductsFiltered) {
                        continue;
                    }

                    $action->group_products_by = 'product';
                    if (!$productsGrouped = $this->groupProducts($this->context->cart->id, $cartProductsFiltered, $action)) {
                        continue;
                    }

                    foreach ($productsGrouped as &$productGrouped) {
                        uasort($productGrouped['products'], $this->makeComparer(array($taxCalculationMethod ? 'price_with_reduction_without_tax' : 'price_with_reduction', $action->apply_discount_sort == 'cheapest' ? SORT_ASC : SORT_DESC)));
                        unset($productGrouped);
                    }

                    foreach ($productsGrouped as $productGrouped) {
                        if ($this->compareValue(0, (int)$productGrouped['cart_quantity'], (int)$action->products_nb_each)) {
                            $remainingTimesToApplyPromo = (($action->nb_repetitions == 'custom') ? min((int)($productGrouped['cart_quantity']/(int)$action->products_nb_each), (int)$action->nb_repetitions_custom) : (int)($productGrouped['cart_quantity']/(int)$action->products_nb_each))*(int)$action->apply_discount_to_nb;

                            $quantityDiscountRule->addDiscountedProducts((int)$action->id_type, $productGrouped['products'], $remainingTimesToApplyPromo*$action->products_nb_each);

                            foreach ($productGrouped['products'] as $key => $product) {
                                $timesToApplyPromoInThisProduct = min($remainingTimesToApplyPromo, (int)$product['cart_quantity'], (($action->apply_discount_to_stock && Configuration::get('PS_STOCK_MANAGEMENT')) ? (int)$product['quantity_available'] : PHP_INT_MAX));

                                $remainingTimesToApplyPromo -= $timesToApplyPromoInThisProduct;

                                $tempCartRule[$key]['gift_product'] = (int)$product['id_product'];
                                $tempCartRule[$key]['gift_product_attribute'] = (int)$product['id_product_attribute'];
                                $tempCartRule[$key]['duplicate_rule'] = $timesToApplyPromoInThisProduct;
                                if (!$remainingTimesToApplyPromo) {
                                    break;
                                }
                            }
                        }
                    }

                    break;

                /**
                 *
                 * All products after X - Fixed discount
                 *
                 */
                case 12:
                    $cartProductsFiltered = $quantityDiscountRule->filterProducts($cartProducts, $action);

                    if (!$cartProductsFiltered) {
                        continue;
                    }

                    if (!$productsGrouped = $this->groupProducts($this->context->cart->id, $cartProductsFiltered, $action)) {
                        continue;
                    }

                    foreach ($productsGrouped as &$productGrouped) {
                        uasort($productGrouped['products'], $this->makeComparer(array($taxCalculationMethod ? 'price_with_reduction_without_tax' : 'price_with_reduction', $action->apply_discount_sort == 'cheapest' ? SORT_ASC : SORT_DESC)));
                        unset($productGrouped);
                    }

                    foreach ($productsGrouped as $productGrouped) {
                        if ((int)$productGrouped['cart_quantity'] > (int)$action->products_nb_each) {
                            $remainingTimesToApplyPromo = (($action->nb_repetitions == 'custom') ? min((int)($productGrouped['cart_quantity']-(int)$action->products_nb_each), (int)$action->nb_repetitions_custom) : (int)($productGrouped['cart_quantity']-(int)$action->products_nb_each));

                            $quantityDiscountRule->addDiscountedProducts((int)$action->id_type, $productGrouped['products'], $productGrouped['cart_quantity'] + $remainingTimesToApplyPromo);

                            foreach ($productGrouped['products'] as $product) {
                                $productPrice = $this->getDiscountedAmount($action, $product, false);

                                $timesToApplyPromoInThisProduct = min($remainingTimesToApplyPromo, (int)$product['cart_quantity'], (($action->apply_discount_to_stock && Configuration::get('PS_STOCK_MANAGEMENT')) ? (int)$product['quantity_available'] : PHP_INT_MAX));

                                $productPrice = self::convertPriceFull($productPrice, $this->context->currency, new Currency((int)$action->reduction_currency), false);

                                if ($productPrice > $action->reduction_amount) {
                                    $reductionAmount += $action->reduction_amount*$timesToApplyPromoInThisProduct;
                                } else {
                                    $reductionAmount += $productPrice*$timesToApplyPromoInThisProduct;
                                }

                                $remainingTimesToApplyPromo -= $timesToApplyPromoInThisProduct;
                                if (!$remainingTimesToApplyPromo) {
                                    break;
                                }
                            }
                        }
                    }

                    if ($reductionAmount > 0) {
                        $tempCartRule[$tempCartRuleCounter]['reduction_amount'] = (($action->reduction_max_amount > 0 && $reductionAmount > $action->reduction_max_amount) ? $action->reduction_max_amount : $reductionAmount);
                        $tempCartRule[$tempCartRuleCounter]['reduction_currency'] = (int)$action->reduction_currency;
                        $tempCartRule[$tempCartRuleCounter]['reduction_tax'] = (int)$action->reduction_tax;
                    }

                    break;

               /**
                 *
                 * All products after X - Percentage discount
                 *
                 */
                case 13:
                    $cartProductsFiltered = $quantityDiscountRule->filterProducts($cartProducts, $action);

                    if (!$cartProductsFiltered) {
                        continue;
                    }

                    if (!$productsGrouped = $this->groupProducts($this->context->cart->id, $cartProductsFiltered, $action)) {
                        continue;
                    }

                    foreach ($productsGrouped as &$productGrouped) {
                        uasort($productGrouped['products'], $this->makeComparer(array($taxCalculationMethod ? 'price_with_reduction_without_tax' : 'price_with_reduction', $action->apply_discount_sort == 'cheapest' ? SORT_ASC : SORT_DESC)));
                        unset($productGrouped);
                    }

                    foreach ($productsGrouped as $productGrouped) {
                        if ($this->compareValue(0, (int)$productGrouped['cart_quantity'], (int)$action->products_nb_each)) {
                            $remainingTimesToApplyPromo = (($action->nb_repetitions == 'custom') ? min((int)($productGrouped['cart_quantity']-(int)$action->products_nb_each), (int)$action->nb_repetitions_custom) : (int)($productGrouped['cart_quantity']-(int)$action->products_nb_each));

                            $quantityDiscountRule->addDiscountedProducts((int)$action->id_type, $productGrouped['products'], $productGrouped['cart_quantity'] + $remainingTimesToApplyPromo);

                            foreach ($productGrouped['products'] as $product) {
                                $unitDiscount = $this->getDiscountedAmount($action, $product, true);

                                $unitDiscountConverted = self::convertPriceFull($unitDiscount, $this->context->currency, new Currency((int)$action->reduction_currency), false);
                                $reductionProductMaxAmountConverted = self::convertPriceFull($action->reduction_product_max_amount, $this->context->currency, new Currency((int)$action->reduction_currency), false);

                                $unitDiscount = (($reductionProductMaxAmountConverted > 0 && $unitDiscountConverted > $reductionProductMaxAmountConverted) ? $reductionProductMaxAmountConverted : $unitDiscountConverted);

                                $timesToApplyPromoInThisProduct = min($remainingTimesToApplyPromo, (int)$product['cart_quantity'], (($action->apply_discount_to_stock && Configuration::get('PS_STOCK_MANAGEMENT')) ? (int)$product['quantity_available'] : PHP_INT_MAX));

                                $reductionAmount += $unitDiscount*$timesToApplyPromoInThisProduct;

                                $remainingTimesToApplyPromo -= $timesToApplyPromoInThisProduct;
                                if (!$remainingTimesToApplyPromo) {
                                    break;
                                }
                            }
                        }
                    }

                    if ($reductionAmount > 0) {
                        $tempCartRule[$tempCartRuleCounter]['reduction_amount'] = (($action->reduction_max_amount > 0 && $reductionAmount > $action->reduction_max_amount) ? $action->reduction_max_amount : $reductionAmount);
                        $tempCartRule[$tempCartRuleCounter]['reduction_currency'] = (int)$action->reduction_currency;
                        $tempCartRule[$tempCartRuleCounter]['reduction_tax'] = (int)$action->reduction_percent_tax;
                    }

                    break;

                /**
                 *
                 * All products after X - Fixed price
                 *
                 */
                case 14:
                    $cartProductsFiltered = $quantityDiscountRule->filterProducts($cartProducts, $action);

                    if (!$cartProductsFiltered) {
                        continue;
                    }

                    if (!$productsGrouped = $this->groupProducts($this->context->cart->id, $cartProductsFiltered, $action)) {
                        continue;
                    }

                    foreach ($productsGrouped as &$productGrouped) {
                        uasort($productGrouped['products'], $this->makeComparer(array($taxCalculationMethod ? 'price_with_reduction_without_tax' : 'price_with_reduction', $action->apply_discount_sort == 'cheapest' ? SORT_ASC : SORT_DESC)));
                        unset($productGrouped);
                    }

                    foreach ($productsGrouped as $productGrouped) {
                        if ($this->compareValue(0, (int)$productGrouped['cart_quantity'], (int)$action->products_nb_each)) {
                            $remainingTimesToApplyPromo = (($action->nb_repetitions == 'custom') ? min((int)($productGrouped['cart_quantity']-(int)$action->products_nb_each), (int)$action->nb_repetitions_custom) : (int)($productGrouped['cart_quantity']-(int)$action->products_nb_each));

                            $quantityDiscountRule->addDiscountedProducts((int)$action->id_type, $productGrouped['products'], $productGrouped['cart_quantity'] + $remainingTimesToApplyPromo);

                            foreach ($productGrouped['products'] as $product) {
                                $productPrice = $this->getDiscountedAmount($action, $product, false);

                                $timesToApplyPromoInThisProduct = min($remainingTimesToApplyPromo, (int)$product['cart_quantity'], (($action->apply_discount_to_stock && Configuration::get('PS_STOCK_MANAGEMENT')) ? (int)$product['quantity_available'] : PHP_INT_MAX));

                                $productPrice = self::convertPriceFull($productPrice, $this->context->currency, new Currency((int)$action->reduction_currency), false);

                                $reductionAmount += ($productPrice-$action->reduction_amount)*$timesToApplyPromoInThisProduct;

                                $remainingTimesToApplyPromo -= $timesToApplyPromoInThisProduct;
                                if (!$remainingTimesToApplyPromo) {
                                    break;
                                }
                            }
                        }
                    }

                    if ($reductionAmount > 0) {
                        $tempCartRule[$tempCartRuleCounter]['reduction_amount'] = (($action->reduction_max_amount > 0 && $reductionAmount > $action->reduction_max_amount) ? $action->reduction_max_amount : $reductionAmount);
                        $tempCartRule[$tempCartRuleCounter]['reduction_currency'] = (int)$action->reduction_currency;
                        $tempCartRule[$tempCartRuleCounter]['reduction_tax'] = (int)$action->reduction_tax;
                    }

                    break;

                /**
                 *
                 * Each group of X - Fixed discount
                 *
                 */
                case 15:
                    $cartProductsFiltered = $quantityDiscountRule->filterProducts($cartProducts, $action);

                    if (!$cartProductsFiltered) {
                        continue;
                    }

                    if (!$productsGrouped = $this->groupProducts($this->context->cart->id, $cartProductsFiltered, $action)) {
                        continue;
                    }

                    foreach ($productsGrouped as &$productGrouped) {
                        uasort($productGrouped['products'], $this->makeComparer(array($taxCalculationMethod ? 'price_with_reduction_without_tax' : 'price_with_reduction', $action->apply_discount_sort == 'cheapest' ? SORT_ASC : SORT_DESC)));
                        unset($productGrouped);
                    }

                    foreach ($productsGrouped as $productGrouped) {
                        if ($this->compareValue(0, (int)$productGrouped['cart_quantity'], (int)$action->products_nb_each)) {
                            $remainingTimesToApplyPromo = (($action->nb_repetitions == 'custom') ? min((int)($productGrouped['cart_quantity']/(int)$action->products_nb_each), (int)$action->nb_repetitions_custom) : (int)($productGrouped['cart_quantity']/(int)$action->products_nb_each));

                            $quantityDiscountRule->addDiscountedProducts((int)$action->id_type, $productGrouped['products'], $remainingTimesToApplyPromo*$action->products_nb_each);

                            foreach ($productGrouped['products'] as $product) {
                                $productPrice = $this->getDiscountedAmount($action, $product, false);

                                $timesToApplyPromoInThisProduct = min($remainingTimesToApplyPromo, (int)$product['cart_quantity'], (($action->apply_discount_to_stock && Configuration::get('PS_STOCK_MANAGEMENT')) ? (int)$product['quantity_available'] : PHP_INT_MAX));

                                $productPrice = self::convertPriceFull($productPrice, $this->context->currency, new Currency((int)$action->reduction_currency), false);

                                if ($productPrice > $action->reduction_amount) {
                                    $reductionAmount += $action->reduction_amount*$timesToApplyPromoInThisProduct;
                                } else {
                                    $reductionAmount += $productPrice*$timesToApplyPromoInThisProduct;
                                }

                                $remainingTimesToApplyPromo -= $timesToApplyPromoInThisProduct;
                                if (!$remainingTimesToApplyPromo) {
                                    break;
                                }
                            }
                        }
                    }

                    if ($reductionAmount > 0) {
                        $tempCartRule[$tempCartRuleCounter]['reduction_amount'] = (($action->reduction_max_amount > 0 && $reductionAmount > $action->reduction_max_amount) ? $action->reduction_max_amount : $reductionAmount);
                        $tempCartRule[$tempCartRuleCounter]['reduction_currency'] = (int)$action->reduction_currency;
                        $tempCartRule[$tempCartRuleCounter]['reduction_tax'] = (int)$action->reduction_tax;
                    }

                    break;

                /**
                 *
                 * Each group of X - Percentage discount
                 *
                 */
                case 16:
                    $cartProductsFiltered = $quantityDiscountRule->filterProducts($cartProducts, $action);

                    if (!$cartProductsFiltered) {
                        continue;
                    }

                    if (!$productsGrouped = $this->groupProducts($this->context->cart->id, $cartProductsFiltered, $action)) {
                        continue;
                    }

                    foreach ($productsGrouped as &$productGrouped) {
                        uasort($productGrouped['products'], $this->makeComparer(array($taxCalculationMethod ? 'price_with_reduction_without_tax' : 'price_with_reduction', $action->apply_discount_sort == 'cheapest' ? SORT_ASC : SORT_DESC)));
                        unset($productGrouped);
                    }

                    foreach ($productsGrouped as $productGrouped) {
                        if ((int)$productGrouped['cart_quantity'] >= (int)$action->products_nb_each) {
                            $remainingTimesToApplyPromo = (($action->nb_repetitions == 'custom') ? min((int)($productGrouped['cart_quantity']/(int)$action->products_nb_each), (int)$action->nb_repetitions_custom) : (int)($productGrouped['cart_quantity']/(int)$action->products_nb_each))*(int)$action->products_nb_each;

                            $groupPrice = 0;
                            $groupAggregate = 0;

                            $quantityDiscountRule->addDiscountedProducts((int)$action->id_type, $productGrouped['products'], $remainingTimesToApplyPromo*$action->products_nb_each);

                            foreach ($productGrouped['products'] as $product) {
                                $productPrice = $this->getDiscountedAmount($action, $product, true);

                                $timesToApplyPromoInThisProduct = min($remainingTimesToApplyPromo, (int)$product['cart_quantity'], (($action->apply_discount_to_stock && Configuration::get('PS_STOCK_MANAGEMENT')) ? (int)$product['quantity_available'] : PHP_INT_MAX));

                                $groupAggregate += $timesToApplyPromoInThisProduct;
                                $groupPrice += $productPrice*$timesToApplyPromoInThisProduct;

                                $remainingTimesToApplyPromo -= $timesToApplyPromoInThisProduct;
                                if (!$remainingTimesToApplyPromo) {
                                    break;
                                }
                            }

                            $reductionAmount += $groupPrice;
                        }
                    }

                    if ($reductionAmount > 0) {
                        $reductionAmount = self::convertPriceFull($reductionAmount, $this->context->currency, new Currency((int)$action->reduction_currency), false);

                        $tempCartRule[$tempCartRuleCounter]['reduction_amount'] = (($action->reduction_max_amount > 0 && $reductionAmount > $action->reduction_max_amount) ? $action->reduction_max_amount : $reductionAmount);
                        $tempCartRule[$tempCartRuleCounter]['reduction_currency'] = (int)$action->reduction_currency;
                        $tempCartRule[$tempCartRuleCounter]['reduction_tax'] = (int)$action->reduction_percent_tax;
                    }

                    break;

                /**
                 *
                 * Each group of X - Fixed price
                 *
                 */
                case 17:
                    $cartProductsFiltered = $quantityDiscountRule->filterProducts($cartProducts, $action);

                    if (!$cartProductsFiltered) {
                        continue;
                    }

                    if (!$productsGrouped = $this->groupProducts($this->context->cart->id, $cartProductsFiltered, $action)) {
                        continue;
                    }

                    foreach ($productsGrouped as &$productGrouped) {
                        uasort($productGrouped['products'], $this->makeComparer(array($taxCalculationMethod ? 'price_with_reduction_without_tax' : 'price_with_reduction', $action->apply_discount_sort == 'cheapest' ? SORT_ASC : SORT_DESC)));
                        unset($productGrouped);
                    }

                    foreach ($productsGrouped as $productGrouped) {
                        if ((int)$productGrouped['cart_quantity'] >= (int)$action->products_nb_each) {
                            $timesToApplyPromo = (($action->nb_repetitions == 'custom') ? min((int)($productGrouped['cart_quantity']/(int)$action->products_nb_each), (int)$action->nb_repetitions_custom) : (int)($productGrouped['cart_quantity']/(int)$action->products_nb_each));

                            $remainingTimesToApplyPromo = $timesToApplyPromo*(int)$action->products_nb_each;

                            $groupPrice = 0;
                            $groupAggregate = 0;

                            $quantityDiscountRule->addDiscountedProducts((int)$action->id_type, $productGrouped['products'], $remainingTimesToApplyPromo*$action->products_nb_each);

                            foreach ($productGrouped['products'] as $product) {
                                $productPrice = $this->getDiscountedAmount($action, $product, false);

                                $timesToApplyPromoInThisProduct = min($remainingTimesToApplyPromo, (int)$product['cart_quantity'], (($action->apply_discount_to_stock && Configuration::get('PS_STOCK_MANAGEMENT')) ? (int)$product['quantity_available'] : PHP_INT_MAX));

                                $groupAggregate += $timesToApplyPromoInThisProduct;
                                $groupPrice += $productPrice*$timesToApplyPromoInThisProduct;

                                $remainingTimesToApplyPromo -= $timesToApplyPromoInThisProduct;
                                if (!$remainingTimesToApplyPromo) {
                                    break;
                                }
                            }

                            $groupPrice = self::convertPriceFull($groupPrice, $this->context->currency, new Currency((int)$action->reduction_currency), false);
                            $reductionAmount += $groupPrice-($action->reduction_amount)*$timesToApplyPromo;
                        }
                    }

                    if ($reductionAmount > 0) {
                        $tempCartRule[$tempCartRuleCounter]['reduction_amount'] = (($action->reduction_max_amount > 0 && $reductionAmount > $action->reduction_max_amount) ? $action->reduction_max_amount : $reductionAmount);
                        $tempCartRule[$tempCartRuleCounter]['reduction_currency'] = (int)$action->reduction_currency;
                        $tempCartRule[$tempCartRuleCounter]['reduction_tax'] = (int)$action->reduction_tax;
                    }

                    break;

                /**
                 *
                 * Each X-th after Y - Fixed discount
                 *
                 */
                case 18:
                    $cartProductsFiltered = $quantityDiscountRule->filterProducts($cartProducts, $action);

                    if (!$cartProductsFiltered) {
                        continue;
                    }

                    if (!$productsGrouped = $this->groupProducts($this->context->cart->id, $cartProductsFiltered, $action)) {
                        continue;
                    }

                    foreach ($productsGrouped as $productGrouped) {
                        if ((int)$productGrouped['cart_quantity'] >= (int)$action->products_nb_each + (int)$action->apply_discount_to_nb) {
                            $remainingTimesToApplyPromo = (($action->nb_repetitions == 'custom') ? min((int)((int)$productGrouped['cart_quantity']-(int)$action->apply_discount_to_nb)/(int)$action->products_nb_each, (int)$action->nb_repetitions_custom) : (int)((int)$productGrouped['cart_quantity']-(int)$action->apply_discount_to_nb)/(int)$action->products_nb_each);

                            $quantityDiscountRule->addDiscountedProducts((int)$action->id_type, $productGrouped['products'], $remainingTimesToApplyPromo*$action->products_nb_each);

                            foreach ($productGrouped['products'] as $product) {
                                $productPrice = $this->getDiscountedAmount($action, $product, false);

                                $timesToApplyPromoInThisProduct = min($remainingTimesToApplyPromo, $product['cart_quantity'], (($action->apply_discount_to_stock && Configuration::get('PS_STOCK_MANAGEMENT')) ? (int)$product['quantity_available'] : PHP_INT_MAX));

                                $productPrice = self::convertPriceFull($productPrice, $this->context->currency, new Currency((int)$action->reduction_currency), false);

                                if ($productPrice > $action->reduction_amount) {
                                    $reductionAmount += $action->reduction_amount*$timesToApplyPromoInThisProduct;
                                } else {
                                    $reductionAmount += $productPrice*$timesToApplyPromoInThisProduct;
                                }

                                $remainingTimesToApplyPromo -= $timesToApplyPromoInThisProduct;
                                if (!$remainingTimesToApplyPromo) {
                                    break;
                                }
                            }
                        }
                    }

                    if ($reductionAmount > 0) {
                        $tempCartRule[$tempCartRuleCounter]['reduction_amount'] = $reductionAmount;
                        $tempCartRule[$tempCartRuleCounter]['reduction_currency'] = (int)$action->reduction_currency;
                        $tempCartRule[$tempCartRuleCounter]['reduction_tax'] = (int)$action->reduction_tax;
                    }

                    break;

                /**
                 *
                 * Each X-th after Y - Percentage discount
                 *
                 */
                case 19:
                    $cartProductsFiltered = $quantityDiscountRule->filterProducts($cartProducts, $action);

                    if (!$cartProductsFiltered) {
                        continue;
                    }

                    if (!$productsGrouped = $this->groupProducts($this->context->cart->id, $cartProductsFiltered, $action)) {
                        continue;
                    }

                    foreach ($productsGrouped as &$productGrouped) {
                        uasort($productGrouped['products'], $this->makeComparer(array($taxCalculationMethod ? 'price_with_reduction_without_tax' : 'price_with_reduction', $action->apply_discount_sort == 'cheapest' ? SORT_ASC : SORT_DESC)));
                        unset($productGrouped);
                    }

                    $previousMod = 0;

                    foreach ($productsGrouped as $productGrouped) {
                        $nbRepetitions = 0;
                        $productGroup = $productGrouped['products'];

                        switch ($action->nb_repetitions) {
                            case 'infinite':
                                while (count($productGrouped['products'])) {
                                    $product = array_shift($productGrouped['products']);

                                    $mod = (int)(($product['cart_quantity'] + $previousMod) % (int)$action->products_nb_each);

                                    if (($product['cart_quantity'] + $previousMod) >= (int)$action->products_nb_each) {
                                        $productPrice = $this->getDiscountedAmount($action, $product, true);

                                        //Check if computed price product is higher than fixed price, if not don't do anything
                                        $productPrice = self::convertPriceFull($productPrice, $this->context->currency, new Currency((int)$action->reduction_currency), false);

                                        $reductionAmount += $productPrice*(int)(($product['cart_quantity']+$previousMod)/(int)$action->products_nb_each);

                                        $nbRepetitions++;
                                    }

                                    $previousMod = $mod;
                                }

                                break;
                            case 'custom':
                                $i = (int)$action->nb_repetitions_custom;
                                while (count($productGrouped['products']) && $i > 0) {
                                    $product = array_shift($productGrouped['products']);

                                    $mod = (int)($product['cart_quantity'] % (int)$action->products_nb_each);
                                    if (($product['cart_quantity'] + $previousMod) >= (int)$action->products_nb_each) {
                                        $productPrice = $this->getDiscountedAmount($action, $product, true);
                                        $productPrice = self::convertPriceFull($productPrice, $this->context->currency, new Currency((int)$action->reduction_currency), false);

                                        $reductionAmount += $productPrice*min((int)(($product['cart_quantity']+$previousMod)/(int)$action->products_nb_each), $i);

                                        $i = $i - (int)(($product['cart_quantity']+$previousMod)/(int)$action->products_nb_each);

                                        $nbRepetitions++;
                                    }

                                    $previousMod = $mod;
                                }

                                break;
                        }

                        $quantityDiscountRule->addDiscountedProducts((int)$action->id_type, $productGroup, $nbRepetitions*$action->products_nb_each);
                    }

                    if ($reductionAmount > 0) {
                        $tempCartRule[$tempCartRuleCounter]['reduction_amount'] = (($action->reduction_max_amount > 0 && $reductionAmount > $action->reduction_max_amount) ? $action->reduction_max_amount : $reductionAmount);
                        $tempCartRule[$tempCartRuleCounter]['reduction_currency'] = (int)$action->reduction_currency;
                        $tempCartRule[$tempCartRuleCounter]['reduction_tax'] = (int)$action->reduction_percent_tax;
                    }

                    break;

                /**
                 *
                 * Each X-th after Y - Fixed price
                 *
                 */
                case 20:
                    $cartProductsFiltered = $quantityDiscountRule->filterProducts($cartProducts, $action);

                    if (!$cartProductsFiltered) {
                        continue;
                    }

                    if (!$productsGrouped = $this->groupProducts($this->context->cart->id, $cartProductsFiltered, $action)) {
                        continue;
                    }

                    foreach ($productsGrouped as &$productGrouped) {
                        uasort($productGrouped['products'], $this->makeComparer(array($taxCalculationMethod ? 'price_with_reduction_without_tax' : 'price_with_reduction', $action->apply_discount_sort == 'cheapest' ? SORT_ASC : SORT_DESC)));
                        unset($productGrouped);
                    }

                    $previousMod = 0;

                    foreach ($productsGrouped as $productGrouped) {
                        $nbRepetitions = 0;
                        $productGroup = $productGrouped['products'];

                        switch ($action->nb_repetitions) {
                            case 'infinite':
                                while (count($productGrouped['products'])) {
                                    $product = array_shift($productGrouped['products']);
                                    $mod = (int)(($product['cart_quantity'] + $previousMod) % (int)$action->products_nb_each);

                                    if (($product['cart_quantity'] + $previousMod) >= (int)$action->products_nb_each) {
                                        //Check if computed price product is higher than fixed price, if not don't do anything
                                        $productPrice = $this->getDiscountedAmount($action, $product, false);
                                        $productPrice = self::convertPriceFull($productPrice, $this->context->currency, new Currency((int)$action->reduction_currency), false);
                                        if ($productPrice - $action->reduction_amount > 0) {
                                            $reductionAmount += ($productPrice - $action->reduction_amount)*(int)(($product['cart_quantity']+$previousMod)/(int)$action->products_nb_each);
                                        }

                                        $nbRepetitions++;
                                    }

                                    $previousMod = $mod;
                                }
                                break;
                            case 'custom':
                                $i = (int)$action->nb_repetitions_custom;
                                while (count($productGrouped['products']) && $i > 0) {
                                    $product = array_shift($productGrouped['products']);
                                    $productPrice = $this->getDiscountedAmount($action, $product, false);

                                    $mod = (int)($product['cart_quantity'] % (int)$action->products_nb_each);

                                    if (($product['cart_quantity'] + $previousMod) >= (int)$action->products_nb_each) {
                                        $productPrice = self::convertPriceFull($productPrice, $this->context->currency, new Currency((int)$action->reduction_currency), false);
                                        if ($productPrice - $action->reduction_amount > 0) {
                                            $reductionAmount += ($productPrice - $action->reduction_amount)*min((int)(($product['cart_quantity']+$previousMod)/(int)$action->products_nb_each), $i);
                                            $i = $i - (int)(($product['cart_quantity']+$previousMod)/(int)$action->products_nb_each);
                                        }

                                        $nbRepetitions++;
                                    }

                                    $previousMod = $mod;
                                }
                                break;
                        }

                        $quantityDiscountRule->addDiscountedProducts((int)$action->id_type, $productGroup, $nbRepetitions*$action->products_nb_each);
                    }

                    if ($reductionAmount > 0) {
                        $tempCartRule[$tempCartRuleCounter]['reduction_amount'] = $reductionAmount;
                        $tempCartRule[$tempCartRuleCounter]['reduction_currency'] = (int)$action->reduction_currency;
                        $tempCartRule[$tempCartRuleCounter]['reduction_tax'] = (int)$action->reduction_tax;
                    }

                    break;

                /**
                 *
                 * Each X spent (over Z) - Get Y (fixed discount)
                 *
                 */
                case 21:
                    $originalProducts = count($cartProducts);
                    $cartProductsFiltered = $quantityDiscountRule->filterProducts($cartProducts, $action);
                    $filteredProducts = count($cartProductsFiltered);

                    if (!$cartProductsFiltered) {
                        continue;
                    }

                    $totalAmount = 0;

                    //Get products amount
                    foreach ($cartProductsFiltered as $product) {
                        if ((int)$action->apply_discount_to_special || !Product::getPriceStatic($product['id_product'], (int)$action->reduction_tax, (isset($product['id_product_attribute']) ? (int)$product['id_product_attribute'] : null), 6, null, true, true, $product['cart_quantity']) > 0) {
                            $productPrice = $this->getDiscountedAmount($action, $product, false);

                            $totalAmount += $productPrice*$product['cart_quantity'];
                        }
                    }

                    //Remove discounts only if there isn't a product filter, as we can not know if a discount is for specific products
                    if ($originalProducts == $filteredProducts) {
                        $totalAmount -= $this->context->cart->getOrderTotal((int)$action->reduction_tax, Cart::ONLY_DISCOUNTS);
                    }

                    //Add shipping cost
                    if ((int)$action->reduction_shipping) {
                        $shippingCost = $this->context->cart->getTotalShippingCost(null, (int)$action->reduction_tax);
                        $totalAmount += $shippingCost;
                    }

                    //Subtract gift products value
                    $totalAmount -= $this->getGiftProductsValue((int)$action->reduction_tax);
                    $totalAmount = self::convertPriceFull($totalAmount, $this->context->currency, new Currency((int)$action->reduction_currency), false);

                    //Subtract amount over
                    $totalAmount -= $action->reduction_buy_over;

                    if ($totalAmount > 0) {
                        $timesToApplyPromo = (int)($totalAmount/$action->reduction_amount);
                        $reductionAmount = $action->reduction_buy_amount * $timesToApplyPromo;

                        $tempCartRule[$tempCartRuleCounter]['reduction_amount'] = (($action->reduction_max_amount > 0 && $reductionAmount > $action->reduction_max_amount) ? $action->reduction_max_amount : $reductionAmount);
                        $tempCartRule[$tempCartRuleCounter]['reduction_currency'] = (int)$action->reduction_currency;
                        $tempCartRule[$tempCartRuleCounter]['reduction_tax'] = (int)$action->reduction_buy_amount_tax;
                    }

                    break;

                /**
                 *
                 * Each X spent (over Z) - Get free gift
                 *
                 */
                case 35:
                    $originalProducts = count($cartProducts);
                    $cartProductsFiltered = $quantityDiscountRule->filterProducts($cartProducts, $action);
                    $filteredProducts = count($cartProductsFiltered);

                    if (!$cartProductsFiltered) {
                        continue;
                    }

                    $totalAmount = 0;

                    //Get products amount
                    foreach ($cartProductsFiltered as $product) {
                        if ((int)$action->apply_discount_to_special || !Product::getPriceStatic($product['id_product'], (int)$action->reduction_tax, (isset($product['id_product_attribute']) ? (int)$product['id_product_attribute'] : null), 6, null, true, true, $product['cart_quantity']) > 0) {
                            $productPrice = $this->getDiscountedAmount($action, $product, false);

                            $totalAmount += $productPrice*$product['cart_quantity'];
                        }
                    }

                    //Remove discounts only if there isn't a product filter, as we can not know if a discount is for specific products
                    if ($originalProducts == $filteredProducts) {
                        $totalAmount -= $this->context->cart->getOrderTotal((int)$action->reduction_tax, Cart::ONLY_DISCOUNTS);
                    }

                    //Add shipping cost
                    if ((int)$action->reduction_shipping) {
                        $shippingCost = $this->context->cart->getTotalShippingCost(null, (int)$action->reduction_tax);
                        $totalAmount += $shippingCost;
                    }

                    //Subtract gift products value
                    $totalAmount -= $this->getGiftProductsValue((int)$action->reduction_tax);
                    $totalAmount = self::convertPriceFull($totalAmount, $this->context->currency, new Currency((int)$action->reduction_currency), false);

                    //Subtract amount over
                    $totalAmount -= $action->reduction_buy_over;

                    if ($totalAmount > 0) {
                        $timesToApplyPromo = (int)($totalAmount/$action->reduction_amount);

                        $tempCartRule[$tempCartRuleCounter]['gift_product'] = (int)$action->gift_product;
                        $tempCartRule[$tempCartRuleCounter]['gift_product_attribute'] = (int)$action->gift_product_attribute;
                        $tempCartRule[$tempCartRuleCounter]['duplicate_rule'] = (int)$timesToApplyPromo;
                    }

                    break;

                /**
                 *
                 * X spent (over Z) Get Y - Percentage discount
                 *
                 */
                case 26:
                    $originalProducts = count($cartProducts);
                    $cartProductsFiltered = $quantityDiscountRule->filterProducts($cartProducts, $action);
                    $filteredProducts = count($cartProductsFiltered);

                    if (!$cartProductsFiltered) {
                        continue;
                    }

                    $totalAmount = 0;
                    //Get products amount
                    foreach ($cartProductsFiltered as $product) {
                        if ((int)$action->apply_discount_to_special || !Product::getPriceStatic($product['id_product'], (int)$action->reduction_percent_tax, (isset($product['id_product_attribute']) ? (int)$product['id_product_attribute'] : null), 6, null, true, true, $product['cart_quantity']) > 0) {
                            $productPrice = $this->getDiscountedAmount($action, $product, false);

                            $totalAmount += $productPrice*$product['cart_quantity'];
                        }
                    }

                    //Remove discounts only if there isn't a product filter, as we can not know if a discount is for specific products
                    if ($originalProducts == $filteredProducts) {
                        $totalAmount -= $this->context->cart->getOrderTotal((int)$action->reduction_percent_tax, Cart::ONLY_DISCOUNTS);
                    }

                    //Add shipping cost
                    if ((int)$action->reduction_shipping) {
                        $shippingCost = $this->context->cart->getTotalShippingCost(null, (int)$action->reduction_percent_tax);
                        $totalAmount += $shippingCost;
                    }

                    //Subtract gift products value
                    $totalAmount -= $this->getGiftProductsValue((int)$action->reduction_percent_tax);
                    $totalAmount = self::convertPriceFull($totalAmount, $this->context->currency, new Currency((int)$action->reduction_currency), false);

                    //Subtract amount over
                    $totalAmount -= $action->reduction_buy_over;

                    if ($totalAmount > 0) {
                        $reductionAmount = $totalAmount*($action->reduction_percent/100);

                        $tempCartRule[$tempCartRuleCounter]['reduction_amount'] = (($action->reduction_max_amount > 0 && $reductionAmount > $action->reduction_max_amount) ? $action->reduction_max_amount : $reductionAmount);
                        $tempCartRule[$tempCartRuleCounter]['reduction_currency'] = (int)$action->reduction_currency;
                        $tempCartRule[$tempCartRuleCounter]['reduction_tax'] = (int)$action->reduction_percent_tax;
                    }

                    break;

                /**
                 *
                 * Buy X
                 *
                 */
                case 22:
                case 23:
                    $cartProductsFiltered = $quantityDiscountRule->filterProducts($cartProducts, $action);

                    if (!$cartProductsFiltered) {
                        $minCoincidences = 0;
                        continue;
                    }

                    if (!$productsGrouped = $this->groupProducts($this->context->cart->id, $cartProductsFiltered, $action)) {
                        $minCoincidences = 0;
                        continue;
                    }

                    $actionsBuyX[$action->id_quantity_discount_rule_action] = $action;
                    $cartProductsFilteredBuyX[$action->id_quantity_discount_rule_action] = $cartProductsFiltered;

                    $minCoincidencesGroup = 0;
                    foreach ($productsGrouped as $productGrouped) {
                        $minCoincidencesGroup += (int)($productGrouped['cart_quantity']/(int)$action->products_nb_each);
                    }

                    $minCoincidences = min($minCoincidences, $minCoincidencesGroup);

                    break;

                /**
                 *
                 * Product discount - Fixed discount
                 *
                 */
                case 27:
                    $cartProductsFiltered = $quantityDiscountRule->filterProducts($cartProducts, $action);

                    if (!$cartProductsFiltered) {
                        continue;
                    }

                    $action->group_products_by = 'all';
                    if (!$productsGrouped = $this->groupProducts($this->context->cart->id, $cartProductsFiltered, $action)) {
                        continue;
                    }

                    foreach ($productsGrouped as &$productGrouped) {
                        uasort($productGrouped['products'], $this->makeComparer(array($taxCalculationMethod ? 'price_with_reduction_without_tax' : 'price_with_reduction', $action->apply_discount_sort == 'cheapest' ? SORT_ASC : SORT_DESC)));
                        unset($productGrouped);
                    }

                    foreach ($productsGrouped as $productGrouped) {
                        $remainingTimesToApplyPromo = (int)$action->products_nb_each;
                        $quantityDiscountRule->addDiscountedProducts((int)$action->id_type, $productGrouped['products'], $remainingTimesToApplyPromo);

                        foreach ($productGrouped['products'] as $product) {
                            $productPrice = $this->getDiscountedAmount($action, $product, false);

                            $timesToApplyPromoInThisProduct = min($remainingTimesToApplyPromo, (int)$product['cart_quantity'], (($action->apply_discount_to_stock && Configuration::get('PS_STOCK_MANAGEMENT')) ? (int)$product['quantity_available'] : PHP_INT_MAX));

                            $productPrice = self::convertPriceFull($productPrice, $this->context->currency, new Currency((int)$action->reduction_currency), false);

                            if ($productPrice > $action->reduction_amount) {
                                $reductionAmount += $action->reduction_amount*$timesToApplyPromoInThisProduct;
                            } else {
                                $reductionAmount += $productPrice*$timesToApplyPromoInThisProduct;
                            }

                            $remainingTimesToApplyPromo -= $timesToApplyPromoInThisProduct;
                            if (!$remainingTimesToApplyPromo) {
                                break;
                            }
                        }
                    }

                    if ($reductionAmount > 0) {
                        $tempCartRule[$tempCartRuleCounter]['reduction_amount'] = (($action->reduction_max_amount > 0 && $reductionAmount > $action->reduction_max_amount) ? $action->reduction_max_amount : $reductionAmount);
                        $tempCartRule[$tempCartRuleCounter]['reduction_currency'] = (int)$action->reduction_currency;
                        $tempCartRule[$tempCartRuleCounter]['reduction_tax'] = (int)$action->reduction_tax;
                    }

                    break;

               /**
                 *
                 * Product discount - Percentage discount
                 *
                 */
                case 28:
                    $cartProductsFiltered = $quantityDiscountRule->filterProducts($cartProducts, $action);

                    if (!$cartProductsFiltered) {
                        continue;
                    }

                    $action->group_products_by = 'all';
                    if (!$productsGrouped = $this->groupProducts($this->context->cart->id, $cartProductsFiltered, $action)) {
                        continue;
                    }

                    foreach ($productsGrouped as &$productGrouped) {
                        uasort($productGrouped['products'], $this->makeComparer(array($taxCalculationMethod ? 'price_with_reduction_without_tax' : 'price_with_reduction', $action->apply_discount_sort == 'cheapest' ? SORT_ASC : SORT_DESC)));
                        unset($productGrouped);
                    }

                    foreach ($productsGrouped as $productGrouped) {
                        $remainingTimesToApplyPromo = (int)$action->products_nb_each;
                        $quantityDiscountRule->addDiscountedProducts((int)$action->id_type, $productGrouped['products'], $remainingTimesToApplyPromo);

                        foreach ($productGrouped['products'] as $product) {
                            $unitDiscount = $this->getDiscountedAmount($action, $product, true);

                            $timesToApplyPromoInThisProduct = min($remainingTimesToApplyPromo, (int)$product['cart_quantity'], (($action->apply_discount_to_stock && Configuration::get('PS_STOCK_MANAGEMENT')) ? (int)$product['quantity_available'] : PHP_INT_MAX));

                            $unitDiscountConverted = self::convertPriceFull($unitDiscount, $this->context->currency, new Currency((int)$action->reduction_currency), false);
                            $reductionProductMaxAmountConverted = self::convertPriceFull($action->reduction_product_max_amount, $this->context->currency, new Currency((int)$action->reduction_currency), false);

                            $unitDiscount = (($reductionProductMaxAmountConverted > 0 && $unitDiscountConverted > $reductionProductMaxAmountConverted) ? $reductionProductMaxAmountConverted : $unitDiscountConverted);

                            $reductionAmount += $unitDiscount*$timesToApplyPromoInThisProduct;

                            $remainingTimesToApplyPromo -= $timesToApplyPromoInThisProduct;
                            if (!$remainingTimesToApplyPromo) {
                                break;
                            }
                        }
                    }

                    if ($reductionAmount > 0) {
                        $tempCartRule[$tempCartRuleCounter]['reduction_amount'] = (($action->reduction_max_amount > 0 && $reductionAmount > $action->reduction_max_amount) ? $action->reduction_max_amount : $reductionAmount);
                        $tempCartRule[$tempCartRuleCounter]['reduction_currency'] = (int)$action->reduction_currency;
                        $tempCartRule[$tempCartRuleCounter]['reduction_tax'] = (int)$action->reduction_percent_tax;
                    }

                    break;

                /**
                 *
                 * Product discount - Fixed price
                 *
                 */
                case 29:
                    $cartProductsFiltered = $quantityDiscountRule->filterProducts($cartProducts, $action);

                    if (!$cartProductsFiltered) {
                        continue;
                    }

                    $action->group_products_by = 'all';
                    if (!$productsGrouped = $this->groupProducts($this->context->cart->id, $cartProductsFiltered, $action)) {
                        continue;
                    }

                    foreach ($productsGrouped as &$productGrouped) {
                        uasort($productGrouped['products'], $this->makeComparer(array($taxCalculationMethod ? 'price_with_reduction_without_tax' : 'price_with_reduction', $action->apply_discount_sort == 'cheapest' ? SORT_ASC : SORT_DESC)));
                        unset($productGrouped);
                    }

                    foreach ($productsGrouped as $productGrouped) {
                        $remainingTimesToApplyPromo = (int)$action->products_nb_each;
                        $quantityDiscountRule->addDiscountedProducts((int)$action->id_type, $productGrouped['products'], $remainingTimesToApplyPromo);

                        foreach ($productGrouped['products'] as $product) {
                            $productPrice = $this->getDiscountedAmount($action, $product, false);

                            $timesToApplyPromoInThisProduct = min($remainingTimesToApplyPromo, (int)$product['cart_quantity'], (($action->apply_discount_to_stock && Configuration::get('PS_STOCK_MANAGEMENT')) ? (int)$product['quantity_available'] : PHP_INT_MAX));

                            $productPrice = self::convertPriceFull($productPrice, $this->context->currency, new Currency((int)$action->reduction_currency), false);

                            $reductionAmount += ($productPrice - $action->reduction_amount) * $timesToApplyPromoInThisProduct;

                            $remainingTimesToApplyPromo -= $timesToApplyPromoInThisProduct;
                            if (!$remainingTimesToApplyPromo) {
                                break;
                            }
                        }
                    }

                    if ($reductionAmount > 0) {
                        $tempCartRule[$tempCartRuleCounter]['reduction_amount'] = (($action->reduction_max_amount > 0 && $reductionAmount > $action->reduction_max_amount) ? $action->reduction_max_amount : $reductionAmount);
                        $tempCartRule[$tempCartRuleCounter]['reduction_currency'] = (int)$action->reduction_currency;
                        $tempCartRule[$tempCartRuleCounter]['reduction_tax'] = (int)$action->reduction_tax;
                    }

                    break;

                /**
                 *
                 * Gift a product
                 *
                 */
                case 30:
                    $tempCartRule[$tempCartRuleCounter]['gift_product'] = (int)$action->gift_product;
                    $tempCartRule[$tempCartRuleCounter]['gift_product_attribute'] = (int)$action->gift_product_attribute;
                    $tempCartRule[$tempCartRuleCounter]['duplicate_rule'] = (int)$action->apply_discount_to_nb;

                    break;

                /**
                 *
                 * Buy more than X units and get discount in all units (quantity discount) - Fixed discount
                 *
                 */
                case 32:
                    $cartProductsFiltered = $quantityDiscountRule->filterProducts($cartProducts, $action);

                    if (!$cartProductsFiltered) {
                        continue;
                    }

                    if (!$productsGrouped = $this->groupProducts($this->context->cart->id, $cartProductsFiltered, $action)) {
                        continue;
                    }

                    foreach ($productsGrouped as $productGrouped) {
                        if ($this->compareValue(0, (int)$productGrouped['cart_quantity'], (int)$action->products_nb_each)) {
                            $quantityDiscountRule->addDiscountedProducts((int)$action->id_type, $productGrouped['products'], (int)$productGrouped['cart_quantity']);

                            foreach ($productGrouped['products'] as $product) {
                                $productPrice = $this->getDiscountedAmount($action, $product, false);
                                $productPrice = self::convertPriceFull($productPrice, $this->context->currency, new Currency((int)$action->reduction_currency), false);

                                if ($productPrice > $action->reduction_amount) {
                                    $reductionAmount += $action->reduction_amount*(int)$product['cart_quantity'];
                                } else {
                                    $reductionAmount += $productPrice*(int)$product['cart_quantity'];
                                }
                            }
                        }
                    }

                    if ($reductionAmount > 0) {
                        $tempCartRule[$tempCartRuleCounter]['reduction_amount'] = (($action->reduction_max_amount > 0 && $reductionAmount > $action->reduction_max_amount) ? $action->reduction_max_amount : $reductionAmount);
                        $tempCartRule[$tempCartRuleCounter]['reduction_currency'] = (int)$action->reduction_currency;
                        $tempCartRule[$tempCartRuleCounter]['reduction_tax'] = (int)$action->reduction_tax;
                    }

                    break;

                /**
                 *
                 * Buy more than X units and get discount in all units (quantity discount) - Percentage discount
                 *
                 */
                case 33:
                    $cartProductsFiltered = $quantityDiscountRule->filterProducts($cartProducts, $action);

                    if (!$cartProductsFiltered) {
                        continue;
                    }

                    if (!$productsGrouped = $this->groupProducts($this->context->cart->id, $cartProductsFiltered, $action)) {
                        continue;
                    }

                    foreach ($productsGrouped as $productGrouped) {
                        if ($this->compareValue(0, (int)$productGrouped['cart_quantity'], (int)$action->products_nb_each)) {
                            $quantityDiscountRule->addDiscountedProducts((int)$action->id_type, $productGrouped['products'], (int)$productGrouped['cart_quantity']);

                            foreach ($productGrouped['products'] as $product) {
                                $unitDiscount = $this->getDiscountedAmount($action, $product, true);

                                $unitDiscountConverted = self::convertPriceFull($unitDiscount, $this->context->currency, new Currency((int)$action->reduction_currency), false);
                                $reductionProductMaxAmountConverted = self::convertPriceFull($action->reduction_product_max_amount, $this->context->currency, new Currency((int)$action->reduction_currency), false);

                                $unitDiscount = (($reductionProductMaxAmountConverted > 0 && $unitDiscountConverted > $reductionProductMaxAmountConverted) ? $reductionProductMaxAmountConverted : $unitDiscountConverted);

                                $reductionAmount += $unitDiscount*(int)$product['cart_quantity'];
                            }
                        }
                    }

                    if ($reductionAmount > 0) {
                        $tempCartRule[$tempCartRuleCounter]['reduction_amount'] = (($action->reduction_max_amount > 0 && $reductionAmount > $action->reduction_max_amount) ? $action->reduction_max_amount : $reductionAmount);
                        $tempCartRule[$tempCartRuleCounter]['reduction_currency'] = (int)$action->reduction_currency;
                        $tempCartRule[$tempCartRuleCounter]['reduction_tax'] = (int)$action->reduction_percent_tax;
                    }

                    break;

                /**
                 *
                 * Buy more than X units and get discount in all units (quantity discount) - Fixed price
                 *
                 */
                case 34:
                    $cartProductsFiltered = $quantityDiscountRule->filterProducts($cartProducts, $action);

                    if (!$cartProductsFiltered) {
                        continue;
                    }

                    if (!$productsGrouped = $this->groupProducts($this->context->cart->id, $cartProductsFiltered, $action)) {
                        continue;
                    }

                    foreach ($productsGrouped as $productGrouped) {
                        if ($this->compareValue(0, (int)$productGrouped['cart_quantity'], (int)$action->products_nb_each)) {
                            $quantityDiscountRule->addDiscountedProducts((int)$action->id_type, $productGrouped['products'], (int)$productGrouped['cart_quantity']);

                            foreach ($productGrouped['products'] as $product) {
                                $productPrice = $this->getDiscountedAmount($action, $product, false);

                                $productPrice = self::convertPriceFull($productPrice, $this->context->currency, new Currency((int)$action->reduction_currency), false);

                                $reductionAmount += ($productPrice-$action->reduction_amount)*(int)$product['cart_quantity'];
                            }
                        }
                    }

                    if ($reductionAmount > 0) {
                        $tempCartRule[$tempCartRuleCounter]['reduction_amount'] = (($action->reduction_max_amount > 0 && $reductionAmount > $action->reduction_max_amount) ? $action->reduction_max_amount : $reductionAmount);
                        $tempCartRule[$tempCartRuleCounter]['reduction_currency'] = (int)$action->reduction_currency;
                        $tempCartRule[$tempCartRuleCounter]['reduction_tax'] = (int)$action->reduction_tax;
                    }

                    break;

                //Add a product with fixed price
                case 36:
                    if ($this->context->cart->updateQty((int)$action->apply_discount_to_nb, (int)$action->gift_product, (int)$action->gift_product_attribute, false, 'up', 0, null, false)) {
                        $cartProducts = $this->context->cart->getProducts();

                        Db::getInstance()->insert('quantity_discount_rule_cart_product', array(
                            'id_cart' => (int)$this->context->cart->id,
                            'id_quantity_discount_rule' => (int)$quantityDiscountRule->id,
                            'id_product' => (int)$action->gift_product,
                            'id_product_attribute' => (int)$action->gift_product_attribute,
                            'quantity' => (int)$action->apply_discount_to_nb,
                        ));

                        //Find product in cart
                        $key = $this->multiArraySearch($cartProducts, array('id_product' => (int)$action->gift_product, 'id_product_attribute' => (int)$action->gift_product_attribute));

                        $productPrice = $this->getDiscountedAmount($action, $cartProducts[$key], false);
                        $productPrice = self::convertPriceFull($productPrice, $this->context->currency, new Currency((int)$action->reduction_currency), false);

                        $tempCartRule[$tempCartRuleCounter]['reduction_amount'] = ($productPrice - $action->reduction_amount) * (int)$action->apply_discount_to_nb;
                        $tempCartRule[$tempCartRuleCounter]['reduction_currency'] = (int)$action->reduction_currency;
                        $tempCartRule[$tempCartRuleCounter]['reduction_tax'] = (int)$action->reduction_tax;
                        $tempCartRule[$tempCartRuleCounter]['id_action_type'] = 1;
                    }

                    break;

                /**
                 *
                 * Product discount - Fixed discount
                 *
                 */
                case 37:
                    $cartProductsFiltered = $quantityDiscountRule->filterProducts($cartProducts, $action);

                    if (!$cartProductsFiltered) {
                        continue;
                    }

                    if (!$productsGrouped = $this->groupProducts($this->context->cart->id, $cartProductsFiltered, $action)) {
                        continue;
                    }

                    foreach ($productsGrouped as $productGrouped) {
                        if (!$this->compareValue(0, $productGrouped['price_with_reduction'], $action->spent_amount_from)
                            || !$this->compareValue(2, $productGrouped['price_with_reduction'], $action->spent_amount_to)) {
                            $reductionAmount += $action->reduction_amount*$productGrouped['cart_quantity'];
                        }
                    }

                    if ($reductionAmount > 0) {
                        $tempCartRule[$tempCartRuleCounter]['reduction_amount'] = (($action->reduction_max_amount > 0 && $reductionAmount > $action->reduction_max_amount) ? $action->reduction_max_amount : $reductionAmount);
                        $tempCartRule[$tempCartRuleCounter]['reduction_currency'] = (int)$action->reduction_currency;
                        $tempCartRule[$tempCartRuleCounter]['reduction_tax'] = (int)$action->reduction_tax;
                    }

                    break;

                case 38:
                    $cartProductsFiltered = $quantityDiscountRule->filterProducts($cartProducts, $action);

                    if (!$cartProductsFiltered) {
                        continue;
                    }

                    if (!$productsGrouped = $this->groupProducts($this->context->cart->id, $cartProductsFiltered, $action)) {
                        continue;
                    }

                    foreach ($productsGrouped as $productGrouped) {
                        if ($this->compareValue(0, $productGrouped['price_with_reduction'], $action->spent_amount_from)
                            && $this->compareValue(2, $productGrouped['price_with_reduction'], $action->spent_amount_to)) {
                            $reductionAmount += $productGrouped['price_with_reduction']*($action->reduction_percent/100);
                        }
                    }

                    if ($reductionAmount > 0) {
                        $tempCartRule[$tempCartRuleCounter]['reduction_amount'] = (($action->reduction_max_amount > 0 && $reductionAmount > $action->reduction_max_amount) ? $action->reduction_max_amount : $reductionAmount);
                        $tempCartRule[$tempCartRuleCounter]['reduction_currency'] = (int)$action->reduction_currency;
                        $tempCartRule[$tempCartRuleCounter]['reduction_tax'] = (int)$action->reduction_tax;
                    }

                    break;

                case 40:
                    $cartProductsFiltered = $quantityDiscountRule->filterProducts($cartProducts, $action);

                    if (!$cartProductsFiltered) {
                        continue;
                    }

                    $action->group_products_by = 'all';
                    if (!$productsGrouped = $this->groupProducts($this->context->cart->id, $cartProductsFiltered, $action)) {
                        continue;
                    }

                    foreach ($productsGrouped as &$productGrouped) {
                        //uasort($productGrouped['products'], $this->makeComparer(array($taxCalculationMethod ? 'price_with_reduction_without_tax' : 'price_with_reduction', $action->apply_discount_sort == 'cheapest' ? SORT_ASC : SORT_DESC)));
                        uasort($productGrouped['products'], $this->makeComparer(array($taxCalculationMethod ? 'price_with_reduction_without_tax' : 'price_with_reduction', SORT_DESC)));
                        unset($productGrouped);
                    }

                    $amount = 0;
                    $nbItems = 0;

                    foreach ($productsGrouped as $productGrouped) {
                        foreach ($productGrouped['products'] as $product) {
                            $unitDiscount = $this->getDiscountedAmount($action, $product, false);
                            $unitDiscountConverted = self::convertPriceFull($unitDiscount, $this->context->currency, new Currency((int)$action->reduction_currency), false);

                            $remainingAmount = ($action->reduction_amount > 0) ? (int)(($action->reduction_amount-$amount)/$unitDiscountConverted) : PHP_INT_MAX;
                            $timesToApplyPromoInThisProduct = min((int)$product['cart_quantity'], ($action->products_nb_each-$nbItems), $remainingAmount);

                            if ($timesToApplyPromoInThisProduct) {
                                $tempCartRule[$tempCartRuleCounter]['gift_product'] = (int)$product['id_product'];
                                $tempCartRule[$tempCartRuleCounter]['gift_product_attribute'] = (int)$product['id_product_attribute'];
                                $tempCartRule[$tempCartRuleCounter]['duplicate_rule'] = (int)$timesToApplyPromoInThisProduct;
                                $tempCartRule[$tempCartRuleCounter]['id_action_type'] = 2;
                                $tempCartRuleCounter++;

                                // We remove the product, it will be added later as a gift
                                $this->context->cart->updateQty($timesToApplyPromoInThisProduct, (int)$product['id_product'], (int)$product['id_product_attribute'], false, 'down', 0, null, false);

                                Db::getInstance()->insert('quantity_discount_rule_cart_product', array(
                                    'id_cart' => (int)$this->context->cart->id,
                                    'id_quantity_discount_rule' => (int)$quantityDiscountRule->id,
                                    'id_product' => (int)$product['id_product'],
                                    'id_product_attribute' => (int)$product['id_product_attribute'],
                                    'quantity' => (int)$timesToApplyPromoInThisProduct,
                                ));

                                $amount += $unitDiscountConverted*$timesToApplyPromoInThisProduct;
                                $nbItems += $timesToApplyPromoInThisProduct;
                            }
                        }
                    }

                    break;


                /**
                 *
                 * Get a discount on A - Fixed discount
                 *
                 */
                case 100:
                    if (!$minCoincidences) {
                        continue;
                    }

                    if (!$cartProductsFiltered = $quantityDiscountRule->filterProducts($cartProducts, $action)) {
                        continue;
                    }

                    if (!$productsGrouped = $this->groupProducts($this->context->cart->id, $cartProductsFiltered, $action)) {
                        continue;
                    }

                    $productsGroupedBuyX = array();
                    foreach (array_keys($actionsBuyX) as $actionId) {
                        $productsGroupedBuyX[$actionId] = $this->groupProducts($this->context->cart->id, $cartProductsFilteredBuyX[$actionId], $actionsBuyX[$actionId]);
                    }

                    //CAUTION! We have to sort products from action Buy X the other way, to start removing the ones that should be discounted later
                    foreach ($productsGrouped as &$productGrouped) {
                        uasort($productGrouped['products'], $this->makeComparer(array($taxCalculationMethod ? 'price_with_reduction_without_tax' : 'price_with_reduction', $action->apply_discount_sort == 'cheapest' ? SORT_ASC : SORT_DESC)));
                        unset($productGrouped);
                    }

                    foreach (array_keys($actionsBuyX) as $actionId) {
                        uasort($cartProductsFilteredBuyX[$actionId], $this->makeComparer(array($taxCalculationMethod ? 'price_with_reduction_without_tax' : 'price_with_reduction', $action->apply_discount_sort == 'cheapest' ? SORT_DESC : SORT_ASC)));
                    }

                    //Para cada accion de Buy X
                    for ($i = 0; $i < $minCoincidences; $i++) {
                        $productsRemoved = array();
                        foreach (array_keys($actionsBuyX) as $actionId) {
                            $unitsRemoved = 0;

                            //Borra cantidad X en productos de X
                            if (is_array($productsGroupedBuyX[$actionId])) {
                                foreach ($productsGroupedBuyX[$actionId] as &$productGroupedBuyX) {
                                    if (is_array($productGroupedBuyX['products'])) {
                                        foreach ($productGroupedBuyX['products'] as $key => &$productBuyX) {
                                            $unitsToRemove = min($productBuyX['cart_quantity'], $actionsBuyX[$actionId]->products_nb_each-$unitsRemoved);
                                            $unitsRemoved += $unitsToRemove;

                                            $productsRemoved[$key] = $unitsToRemove;

                                            $quantityDiscountRule->addDiscountedProducts((int)$action->id_type, $productGroupedBuyX['products'], $unitsToRemove);

                                            if ($productBuyX['cart_quantity'] == $unitsToRemove) {
                                                unset($productGroupedBuyX['products'][$key]);
                                            } else {
                                                $productBuyX['cart_quantity'] -= $unitsToRemove;
                                            }

                                            if ($productGroupedBuyX['cart_quantity'] == $unitsToRemove) {
                                                unset($productsGroupedBuyX[$actionId]);
                                            } else {
                                                $productGroupedBuyX['cart_quantity'] -= $unitsToRemove;
                                            }

                                            if ($actionsBuyX[$actionId]->products_nb_each == $unitsRemoved) {
                                                break 2;
                                            }
                                        }
                                        unset($productBuyX);
                                    }
                                }
                                unset($productGroupedBuyX);
                            }
                        }

                        //Borra cantidad X en productos de Y
                        if ($unitsRemoved) {
                            foreach ($productsGrouped as &$productGrouped) {
                                foreach ($productGrouped['products'] as $key2 => &$product) {
                                    if (in_array($key2, array_keys($productsRemoved))) {
                                        $product['cart_quantity'] -= $productsRemoved[$key2];
                                        $productGrouped['cart_quantity'] -= $productsRemoved[$key2];
                                    }
                                }
                                unset($product);
                            }
                            unset($productGrouped);

                            foreach ($productsGrouped as &$productGrouped) {
                                if ((int)$productGrouped['cart_quantity'] >= (int)$action->apply_discount_to_nb) {
                                    $remainingTimesToApplyPromo = min((int)$productGrouped['cart_quantity'], (int)$action->apply_discount_to_nb);
                                    $quantityDiscountRule->addDiscountedProducts((int)$action->id_type, $productGrouped['products'], $remainingTimesToApplyPromo);

                                    foreach ($productGrouped['products'] as &$product) {
                                        $productPrice = $this->getDiscountedAmount($action, $product, false);

                                        $timesToApplyPromoInThisProduct = min($remainingTimesToApplyPromo, (int)$product['cart_quantity'], (($action->apply_discount_to_stock && Configuration::get('PS_STOCK_MANAGEMENT')) ? (int)$product['quantity_available'] : PHP_INT_MAX));

                                        $productPrice = self::convertPriceFull($productPrice, $this->context->currency, new Currency((int)$action->reduction_currency), false);

                                        if ($productPrice > $action->reduction_amount) {
                                            $reductionAmount += $action->reduction_amount*$timesToApplyPromoInThisProduct;
                                        } else {
                                            $reductionAmount += $productPrice*$timesToApplyPromoInThisProduct;
                                        }

                                        $product['cart_quantity'] -= $timesToApplyPromoInThisProduct;
                                        $productGrouped['cart_quantity'] -= $timesToApplyPromoInThisProduct;

                                        $remainingTimesToApplyPromo -= $timesToApplyPromoInThisProduct;
                                        if (!$remainingTimesToApplyPromo) {
                                            break;
                                        }
                                    }
                                }
                            }
                            unset($productGrouped);
                        }
                    }

                    if ($reductionAmount > 0) {
                        if (isset($tempCartRule[$tempCartRuleCounter]['reduction_amount'])) {
                            $tempCartRule[$tempCartRuleCounter]['reduction_amount'] += $reductionAmount;
                        } else {
                            $tempCartRule[$tempCartRuleCounter]['reduction_amount'] = $reductionAmount;
                        }
                        $tempCartRule[$tempCartRuleCounter]['reduction_currency'] = (int)$action->reduction_currency;
                        $tempCartRule[$tempCartRuleCounter]['reduction_tax'] = (int)$action->reduction_tax;
                    }

                    break;

                /**
                 *
                 * Get a discount on A - Percentage discount
                 *
                 */
                case 101:
                    if (!$minCoincidences) {
                        continue;
                    }

                    if (!$cartProductsFiltered = $quantityDiscountRule->filterProducts($cartProducts, $action)) {
                        continue;
                    }

                    if (!$productsGrouped = $this->groupProducts($this->context->cart->id, $cartProductsFiltered, $action)) {
                        continue;
                    }

                    $productsGroupedBuyX= array();
                    foreach (array_keys($actionsBuyX) as $actionId) {
                        $productsGroupedBuyX[$actionId] = $this->groupProducts($this->context->cart->id, $cartProductsFilteredBuyX[$actionId], $actionsBuyX[$actionId]);
                    }

                    //CAUTION! We have to sort products from action Buy X the other way, to start removing the ones that should be discounted later
                    foreach ($productsGrouped as &$productGrouped) {
                        uasort($productGrouped['products'], $this->makeComparer(array($taxCalculationMethod ? 'price_with_reduction_without_tax' : 'price_with_reduction', $action->apply_discount_sort == 'cheapest' ? SORT_ASC : SORT_DESC)));
                        unset($productGrouped);
                    }

                    foreach (array_keys($actionsBuyX) as $actionId) {
                        uasort($cartProductsFilteredBuyX[$actionId], $this->makeComparer(array($taxCalculationMethod ? 'price_with_reduction_without_tax' : 'price_with_reduction', $action->apply_discount_sort == 'cheapest' ? SORT_DESC : SORT_ASC)));
                    }

                    $reductionAmount = 0;

                    //Para cada accion de Buy X
                    for ($i = 0; $i < $minCoincidences; $i++) {
                        $productsRemoved = array();
                        foreach (array_keys($actionsBuyX) as $actionId) {
                            $unitsRemoved = 0;

                            //Borra cantidad X en productos de X
                            if (is_array($productsGroupedBuyX[$actionId])) {
                                foreach ($productsGroupedBuyX[$actionId] as &$productGroupedBuyX) {
                                    if (is_array($productGroupedBuyX['products'])) {
                                        foreach ($productGroupedBuyX['products'] as $key => &$productBuyX) {
                                            $unitsToRemove = min($productBuyX['cart_quantity'], $actionsBuyX[$actionId]->products_nb_each-$unitsRemoved);
                                            $unitsRemoved += $unitsToRemove;

                                            $productsRemoved[$key] = $unitsToRemove;

                                            $quantityDiscountRule->addDiscountedProducts((int)$action->id_type, $productGroupedBuyX['products'], $unitsToRemove);

                                            if ($productBuyX['cart_quantity'] == $unitsToRemove) {
                                                unset($productGroupedBuyX['products'][$key]);
                                            } else {
                                                $productBuyX['cart_quantity'] -= $unitsToRemove;
                                            }

                                            if ($productGroupedBuyX['cart_quantity'] == $unitsToRemove) {
                                                unset($productsGroupedBuyX[$actionId]);
                                            } else {
                                                $productGroupedBuyX['cart_quantity'] -= $unitsToRemove;
                                            }

                                            if ($actionsBuyX[$actionId]->products_nb_each == $unitsRemoved) {
                                                break 2;
                                            }
                                        }
                                        unset($productBuyX);
                                    }
                                }
                                unset($productGroupedBuyX);
                            }
                        }

                        //Borra cantidad X en productos de Y
                        if ($unitsRemoved) {
                            foreach ($productsGrouped as &$productGrouped) {
                                foreach ($productGrouped['products'] as $key2 => &$product) {
                                    if (in_array($key2, array_keys($productsRemoved))) {
                                        $product['cart_quantity'] -= $productsRemoved[$key2];
                                        $productGrouped['cart_quantity'] -= $productsRemoved[$key2];
                                    }
                                }
                                unset($product);
                            }
                            unset($productGrouped);

                            foreach ($productsGrouped as &$productGrouped) {
                                if ((int)$productGrouped['cart_quantity'] >= (int)$action->apply_discount_to_nb) {
                                    $remainingTimesToApplyPromo = min((int)$productGrouped['cart_quantity'], (int)$action->apply_discount_to_nb);
                                    $quantityDiscountRule->addDiscountedProducts((int)$action->id_type, $productGrouped['products'], $remainingTimesToApplyPromo);

                                    foreach ($productGrouped['products'] as &$product) {
                                        $productPrice = $this->getDiscountedAmount($action, $product, true);

                                        $timesToApplyPromoInThisProduct = min($remainingTimesToApplyPromo, (int)$product['cart_quantity'], (($action->apply_discount_to_stock && Configuration::get('PS_STOCK_MANAGEMENT')) ? (int)$product['quantity_available'] : PHP_INT_MAX));

                                        $productPrice = self::convertPriceFull($productPrice, $this->context->currency, new Currency((int)$action->reduction_currency), false);

                                        $reductionAmount += $productPrice*$timesToApplyPromoInThisProduct;

                                        $product['cart_quantity'] -= $timesToApplyPromoInThisProduct;
                                        $productGrouped['cart_quantity'] -= $timesToApplyPromoInThisProduct;

                                        $remainingTimesToApplyPromo -= $timesToApplyPromoInThisProduct;
                                        if (!$remainingTimesToApplyPromo) {
                                            break;
                                        }
                                    }
                                }
                            }
                        }
                    }

                    if ($reductionAmount > 0) {
                        $reductionMaxAmountConverted = self::convertPriceFull($action->reduction_max_amount, $this->context->currency, new Currency((int)$action->reduction_max_currency));
                        if (isset($tempCartRule[$tempCartRuleCounter]['reduction_amount'])) {
                            $tempCartRule[$tempCartRuleCounter]['reduction_amount'] += (($reductionMaxAmountConverted > 0 && $reductionAmount > $reductionMaxAmountConverted) ? $reductionMaxAmountConverted : $reductionAmount);
                        } else {
                            $tempCartRule[$tempCartRuleCounter]['reduction_amount'] = (($reductionMaxAmountConverted > 0 && $reductionAmount > $reductionMaxAmountConverted) ? $reductionMaxAmountConverted : $reductionAmount);
                        }
                        $tempCartRule[$tempCartRuleCounter]['reduction_currency'] = (int)$action->reduction_currency;
                        $tempCartRule[$tempCartRuleCounter]['reduction_tax'] = (int)$action->reduction_percent_tax;
                    }

                    break;

                /**
                 *
                 * Get a discount on A - Fixed price
                 *
                 */
                case 102:
                    if (!$minCoincidences) {
                        continue;
                    }

                    if (!$cartProductsFiltered = $quantityDiscountRule->filterProducts($cartProducts, $action)) {
                        continue;
                    }

                    if (!$productsGrouped = $this->groupProducts($this->context->cart->id, $cartProductsFiltered, $action)) {
                        continue;
                    }

                    $productsGroupedBuyX = array();
                    foreach (array_keys($actionsBuyX) as $actionId) {
                        $productsGroupedBuyX[$actionId] = $this->groupProducts($this->context->cart->id, $cartProductsFilteredBuyX[$actionId], $actionsBuyX[$actionId]);
                    }

                    //CAUTION! We have to sort products from action Buy X the other way, to start removing the ones that should be discounted later
                    foreach ($productsGrouped as &$productGrouped) {
                        uasort($productGrouped['products'], $this->makeComparer(array($taxCalculationMethod ? 'price_with_reduction_without_tax' : 'price_with_reduction', $action->apply_discount_sort == 'cheapest' ? SORT_ASC : SORT_DESC)));
                        unset($productGrouped);
                    }

                    foreach (array_keys($actionsBuyX) as $actionId) {
                        uasort($cartProductsFilteredBuyX[$actionId], $this->makeComparer(array($taxCalculationMethod ? 'price_with_reduction_without_tax' : 'price_with_reduction', $action->apply_discount_sort == 'cheapest' ? SORT_DESC : SORT_ASC)));
                    }

                    //Para cada accion de Buy X
                    for ($i = 0; $i < $minCoincidences; $i++) {
                        $productsRemoved = array();
                        foreach (array_keys($actionsBuyX) as $actionId) {
                            $unitsRemoved = 0;

                            //Borra cantidad X en productos de X
                            if (is_array($productsGroupedBuyX[$actionId])) {
                                foreach ($productsGroupedBuyX[$actionId] as &$productGroupedBuyX) {
                                    if (is_array($productGroupedBuyX['products'])) {
                                        foreach ($productGroupedBuyX['products'] as $key => &$productBuyX) {
                                            $unitsToRemove = min($productBuyX['cart_quantity'], $actionsBuyX[$actionId]->products_nb_each-$unitsRemoved);
                                            $unitsRemoved += $unitsToRemove;

                                            $productsRemoved[$key] = $unitsToRemove;

                                            $quantityDiscountRule->addDiscountedProducts((int)$action->id_type, $productGroupedBuyX['products'], $unitsToRemove);

                                            if ($productBuyX['cart_quantity'] == $unitsToRemove) {
                                                unset($productGroupedBuyX['products'][$key]);
                                            } else {
                                                $productBuyX['cart_quantity'] -= $unitsToRemove;
                                            }

                                            if ($productGroupedBuyX['cart_quantity'] == $unitsToRemove) {
                                                unset($productsGroupedBuyX[$actionId]);
                                            } else {
                                                $productGroupedBuyX['cart_quantity'] -= $unitsToRemove;
                                            }

                                            if ($actionsBuyX[$actionId]->products_nb_each == $unitsRemoved) {
                                                break 2;
                                            }
                                        }
                                        unset($productBuyX);
                                    }
                                }
                                unset($productGroupedBuyX);
                            }
                        }

                        //Borra cantidad X en productos de Y
                        if ($unitsRemoved) {
                            foreach ($productsGrouped as &$productGrouped) {
                                foreach ($productGrouped['products'] as $key2 => &$product) {
                                    if (in_array($key2, array_keys($productsRemoved))) {
                                        $product['cart_quantity'] -= $productsRemoved[$key2];
                                        $productGrouped['cart_quantity'] -= $productsRemoved[$key2];
                                    }
                                }
                                unset($product);
                            }
                            unset($productGrouped);

                            foreach ($productsGrouped as &$productGrouped) {
                                if ($this->compareValue(0, (int)$productGrouped['cart_quantity'], (int)$action->apply_discount_to_nb)) {
                                    $remainingTimesToApplyPromo = min((int)$productGrouped['cart_quantity'], (int)$action->apply_discount_to_nb);
                                    $quantityDiscountRule->addDiscountedProducts((int)$action->id_type, $productGrouped['products'], $remainingTimesToApplyPromo);

                                    foreach ($productGrouped['products'] as &$product) {
                                        $productPrice = $this->getDiscountedAmount($action, $product, false);

                                        $timesToApplyPromoInThisProduct = min($remainingTimesToApplyPromo, (int)$product['cart_quantity'], (($action->apply_discount_to_stock && Configuration::get('PS_STOCK_MANAGEMENT')) ? (int)$product['quantity_available'] : PHP_INT_MAX));

                                        $productPrice = self::convertPriceFull($productPrice, $this->context->currency, new Currency((int)$action->reduction_currency), false);

                                        $reductionAmount += ($productPrice-$action->reduction_amount)*$timesToApplyPromoInThisProduct;
                                        $product['cart_quantity'] -= $timesToApplyPromoInThisProduct;
                                        $productGrouped['cart_quantity'] -= $timesToApplyPromoInThisProduct;

                                        $remainingTimesToApplyPromo -= $timesToApplyPromoInThisProduct;
                                        if (!$remainingTimesToApplyPromo) {
                                            break;
                                        }
                                    }
                                }
                            }
                            unset($productGrouped);
                        }
                    }

                    if ($reductionAmount > 0) {
                        if (isset($tempCartRule[$tempCartRuleCounter]['reduction_amount'])) {
                            $tempCartRule[$tempCartRuleCounter]['reduction_amount'] += $reductionAmount;
                        } else {
                            $tempCartRule[$tempCartRuleCounter]['reduction_amount'] = $reductionAmount;
                        }
                        $tempCartRule[$tempCartRuleCounter]['reduction_currency'] = (int)$action->reduction_currency;
                        $tempCartRule[$tempCartRuleCounter]['reduction_tax'] = (int)$action->reduction_tax;
                    }

                    break;

                case 107:
                    if (!$minCoincidences) {
                        continue;
                    }

                    $productsGroupedBuyX = array();
                    foreach (array_keys($actionsBuyX) as $actionId) {
                        $productsGroupedBuyX[$actionId] = $this->groupProducts($this->context->cart->id, $cartProductsFilteredBuyX[$actionId], $actionsBuyX[$actionId]);
                    }

                    //Para cada accion de Buy X
                    for ($i = 0; $i < $minCoincidences; $i++) {
                        $productsRemoved = array();
                        foreach (array_keys($actionsBuyX) as $actionId) {
                            $unitsRemoved = 0;

                            //Borra cantidad X en productos de X
                            if (is_array($productsGroupedBuyX[$actionId])) {
                                foreach ($productsGroupedBuyX[$actionId] as &$productGroupedBuyX) {
                                    if (is_array($productGroupedBuyX['products'])) {
                                        foreach ($productGroupedBuyX['products'] as $key => &$productBuyX) {
                                            $unitsToRemove = min($productBuyX['cart_quantity'], $actionsBuyX[$actionId]->products_nb_each-$unitsRemoved);
                                            $unitsRemoved += $unitsToRemove;

                                            $productsRemoved[$key] = $unitsToRemove;

                                            $this->addDiscountedProducts($action, $productGroupedBuyX['products'], $unitsToRemove);

                                            if ($productBuyX['cart_quantity'] == $unitsToRemove) {
                                                unset($productGroupedBuyX['products'][$key]);
                                            } else {
                                                $productBuyX['cart_quantity'] -= $unitsToRemove;
                                            }

                                            if ($productGroupedBuyX['cart_quantity'] == $unitsToRemove) {
                                                unset($productsGroupedBuyX[$actionId]);
                                            } else {
                                                $productGroupedBuyX['cart_quantity'] -= $unitsToRemove;
                                            }

                                            if ($actionsBuyX[$actionId]->products_nb_each == $unitsRemoved) {
                                                break 2;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }

                    //Add product
                    if ($this->context->cart->updateQty((int)$action->apply_discount_to_nb*(int)$minCoincidences, (int)$action->gift_product, (int)$action->gift_product_attribute, false, 'up', 0, null, false)) {
                        $cartProducts = $this->context->cart->getProducts();

                        Db::getInstance()->insert('quantity_discount_rule_cart_product', array(
                            'id_cart' => (int)$this->context->cart->id,
                            'id_quantity_discount_rule' => (int)$quantityDiscountRule->id,
                            'id_product' => (int)$action->gift_product,
                            'id_product_attribute' => (int)$action->gift_product_attribute,
                            'quantity' => (int)$action->apply_discount_to_nb*(int)$minCoincidences,
                        ));

                        //Find product in cart
                        $key = $this->multiArraySearch($cartProducts, array('id_product' => (int)$action->gift_product, 'id_product_attribute' => (int)$action->gift_product_attribute));
                        $productPrice = $this->getDiscountedAmount($action, $cartProducts[$key], false);
                        $productPrice = self::convertPriceFull($productPrice, $this->context->currency, new Currency((int)$action->reduction_currency), false);

                        $tempCartRule[$tempCartRuleCounter]['reduction_amount'] = ($productPrice - $action->reduction_amount)*(int)$action->apply_discount_to_nb*(int)$minCoincidences;
                        $tempCartRule[$tempCartRuleCounter]['reduction_currency'] = (int)$action->reduction_currency;
                        $tempCartRule[$tempCartRuleCounter]['reduction_tax'] = (int)$action->reduction_tax;
                        $tempCartRule[$tempCartRuleCounter]['id_action_type'] = 1;
                    }
                    break;

                /**
                 *
                 * Gift a product
                 *
                 */
                case 103:
                    if (!$minCoincidences) {
                        continue;
                    }

                    $productsGroupedBuyX = array();
                    foreach (array_keys($actionsBuyX) as $actionId) {
                        $productsGroupedBuyX[$actionId] = $this->groupProducts($this->context->cart->id, $cartProductsFilteredBuyX[$actionId], $actionsBuyX[$actionId]);
                    }

                    //Para cada accion de Buy X
                    for ($i = 0; $i < $minCoincidences; $i++) {
                        $productsRemoved = array();
                        foreach (array_keys($actionsBuyX) as $actionId) {
                            $unitsRemoved = 0;

                            //Borra cantidad X en productos de X
                            if (is_array($productsGroupedBuyX[$actionId])) {
                                foreach ($productsGroupedBuyX[$actionId] as &$productGroupedBuyX) {
                                    if (is_array($productGroupedBuyX['products'])) {
                                        foreach ($productGroupedBuyX['products'] as $key => &$productBuyX) {
                                            $unitsToRemove = min($productBuyX['cart_quantity'], $actionsBuyX[$actionId]->products_nb_each-$unitsRemoved);
                                            $unitsRemoved += $unitsToRemove;

                                            $productsRemoved[$key] = $unitsToRemove;

                                            $quantityDiscountRule->addDiscountedProducts((int)$action->id_type, $productGroupedBuyX['products'], $unitsToRemove);

                                            if ($productBuyX['cart_quantity'] == $unitsToRemove) {
                                                unset($productGroupedBuyX['products'][$key]);
                                            } else {
                                                $productBuyX['cart_quantity'] -= $unitsToRemove;
                                            }

                                            if ($productGroupedBuyX['cart_quantity'] == $unitsToRemove) {
                                                unset($productsGroupedBuyX[$actionId]);
                                            } else {
                                                $productGroupedBuyX['cart_quantity'] -= $unitsToRemove;
                                            }

                                            if ($actionsBuyX[$actionId]->products_nb_each == $unitsRemoved) {
                                                break 2;
                                            }
                                        }
                                        unset($productBuyX);
                                    }
                                }
                                unset($productGroupedBuyX);
                            }
                        }
                    }

                    $tempCartRule[$tempCartRuleCounter]['gift_product'] = (int)$action->gift_product;
                    $tempCartRule[$tempCartRuleCounter]['gift_product_attribute'] = (int)$action->gift_product_attribute;
                    $tempCartRule[$tempCartRuleCounter]['duplicate_rule'] = (int)$minCoincidences*(int)$action->apply_discount_to_nb;

                    break;

                /**
                 *
                 * Product pack - Fixed discount
                 *
                 */
                case 104:
                    if (!$minCoincidences) {
                        continue;
                    }

                    /*
                     * $cartProductsFilteredBuyX are the products that complies actions 24
                     * We could just do $action->reduction_amount*$minCoincidences, but we need to know the price of the product
                     * because is product price is lower than reduction_amount, we reduce the product price
                    */
                    foreach (array_keys($actionsBuyX) as $actionId) {
                        uasort($cartProductsFilteredBuyX[$actionId], $this->makeComparer(array($taxCalculationMethod ? 'price_with_reduction_without_tax' : 'price_with_reduction', $action->apply_discount_sort == 'cheapest' ? SORT_ASC : SORT_DESC)));
                    }

                    /*
                     * For all the coincidences, we calculate the product pack amount (we get products regarding the quantity from each condition)
                    */
                    for ($i = 0; $i < $minCoincidences; $i++) {
                        $computedProductPackPrice = 0;
                        foreach (array_keys($actionsBuyX) as $actionId) {
                            $unitsRemoved = 0;

                            if (is_array($cartProductsFilteredBuyX[$actionId])) {
                                foreach ($cartProductsFilteredBuyX[$actionId] as $key => &$product) {
                                    $unitsToRemove = min($product['cart_quantity'], $actionsBuyX[$actionId]->products_nb_each-$unitsRemoved);
                                    $unitsRemoved += $unitsToRemove;

                                    $quantityDiscountRule->addDiscountedProducts((int)$action->id_type, $cartProductsFilteredBuyX[$actionId], $unitsToRemove);

                                    $unitDiscount = $this->getDiscountedAmount($action, $product, false);
                                    $unitDiscountConverted = self::convertPriceFull($unitDiscount, $this->context->currency, new Currency((int)$action->reduction_currency));

                                    $computedProductPackPrice += $unitDiscountConverted;

                                    if ($product['cart_quantity'] == $unitsToRemove) {
                                        unset($cartProductsFilteredBuyX[$actionId][$key]);
                                    } else {
                                        $product['cart_quantity'] -= $unitsToRemove;
                                    }

                                    if ($actionsBuyX[$actionId]->products_n0b_each == $unitsRemoved) {
                                        break;
                                    }
                                }
                                unset($product);
                            }
                        }

                        if ($computedProductPackPrice > $action->reduction_amount) {
                            $reductionAmount += $action->reduction_amount;
                        } else {
                            $reductionAmount += $computedProductPackPrice;
                        }
                    }

                    if ($reductionAmount > 0) {
                        if (isset($tempCartRule[$tempCartRuleCounter]['reduction_amount'])) {
                            $tempCartRule[$tempCartRuleCounter]['reduction_amount'] += $reductionAmount;
                        } else {
                            $tempCartRule[$tempCartRuleCounter]['reduction_amount'] = $reductionAmount;
                        }
                        $tempCartRule[$tempCartRuleCounter]['reduction_currency'] = (int)$action->reduction_currency;
                        $tempCartRule[$tempCartRuleCounter]['reduction_tax'] = (int)$action->reduction_tax;
                    }

                    break;

                /**
                 *
                 * Product pack - Percentage discount
                 *
                 */
                case 105:
                    if (!$minCoincidences) {
                        continue;
                    }

                    /*
                     * $cartProductsFilteredBuyX are the products that complies actions 24
                     * We could just do $action->reduction_amount*$minCoincidences, but we need to know the price of the product
                     * because is product price is lower than reduction_amount, we reduce the product price
                    */
                    foreach (array_keys($actionsBuyX) as $actionId) {
                        uasort($cartProductsFilteredBuyX[$actionId], $this->makeComparer(array($taxCalculationMethod ? 'price_with_reduction_without_tax' : 'price_with_reduction', $action->apply_discount_sort == 'cheapest' ? SORT_ASC : SORT_DESC)));
                    }

                    /*
                     * For all the coincidences, we calculate the product pack amount (we get products regarding the quantity from each condition)
                    */
                    for ($i = 0; $i < $minCoincidences; $i++) {
                        $computedProductPackPrice = 0;
                        foreach (array_keys($actionsBuyX) as $actionId) {
                            $unitsRemoved = 0;

                            if (is_array($cartProductsFilteredBuyX[$actionId])) {
                                foreach ($cartProductsFilteredBuyX[$actionId] as $key => &$product) {
                                    $unitsToRemove = min($product['cart_quantity'], $actionsBuyX[$actionId]->products_nb_each-$unitsRemoved);
                                    $unitsRemoved += $unitsToRemove;

                                    $quantityDiscountRule->addDiscountedProducts((int)$action->id_type, $cartProductsFilteredBuyX[$actionId], $unitsToRemove);

                                    $unitDiscount = $this->getDiscountedAmount($action, $product, true);

                                    $unitDiscountConverted = self::convertPriceFull($unitDiscount, $this->context->currency, new Currency((int)$action->reduction_currency));
                                    $reductionProductMaxAmountConverted = self::convertPriceFull($action->reduction_product_max_amount, $this->context->currency, new Currency((int)$action->reduction_currency));

                                    $reductionAmount += (($reductionProductMaxAmountConverted > 0 && $unitDiscountConverted > $reductionProductMaxAmountConverted) ? $reductionProductMaxAmountConverted : $unitDiscountConverted);

                                    if ($product['cart_quantity'] == $unitsToRemove) {
                                        unset($cartProductsFilteredBuyX[$actionId][$key]);
                                    } else {
                                        $product['cart_quantity'] -= $unitsToRemove;
                                    }

                                    if ($actionsBuyX[$actionId]->products_nb_each == $unitsRemoved) {
                                        break;
                                    }
                                }
                                unset($product);
                            }
                        }
                    }

                    if ($reductionAmount > 0) {
                        $tempCartRule[$tempCartRuleCounter]['reduction_amount'] = (($action->reduction_max_amount > 0 && $reductionAmount > $action->reduction_max_amount) ? $action->reduction_max_amount : $reductionAmount);
                        $tempCartRule[$tempCartRuleCounter]['reduction_currency'] = (int)$action->reduction_currency;
                        $tempCartRule[$tempCartRuleCounter]['reduction_tax'] = (int)$action->reduction_percent_tax;
                    }

                    break;

                /**
                 *
                 * Product pack - Fixed price
                 *
                 */
                case 106:
                    if (!$minCoincidences) {
                        continue;
                    }

                    /*
                     * $cartProductsFilteredBuyX are the products that complies actions 24
                     * We could just do $action->reduction_amount*$minCoincidences, but we need to know the price of the product
                     * because is product price is lower than reduction_amount, we reduce the product price
                    */
                    foreach (array_keys($actionsBuyX) as $actionId) {
                        uasort($cartProductsFilteredBuyX[$actionId], $this->makeComparer(array($taxCalculationMethod ? 'price_with_reduction_without_tax' : 'price_with_reduction', $action->apply_discount_sort == 'cheapest' ? SORT_ASC : SORT_DESC)));
                    }

                    /*
                     * For all the coincidences, we calculate the product pack amount (we get products regarding the quantity from each condition)
                    */
                    for ($i = 0; $i < $minCoincidences; $i++) {
                        $computedProductPackPrice = 0;
                        foreach (array_keys($actionsBuyX) as $actionId) {
                            $unitsRemoved = 0;

                            if (is_array($cartProductsFilteredBuyX[$actionId])) {
                                foreach ($cartProductsFilteredBuyX[$actionId] as $key => &$product) {
                                    $unitsToRemove = min($product['cart_quantity'], $actionsBuyX[$actionId]->products_nb_each-$unitsRemoved);
                                    $unitsRemoved += $unitsToRemove;

                                    $quantityDiscountRule->addDiscountedProducts((int)$action->id_type, $cartProductsFilteredBuyX[$actionId], $unitsToRemove);

                                    $unitDiscount = $this->getDiscountedAmount($action, $product, false)*$unitsToRemove;

                                    $unitDiscountConverted = self::convertPriceFull($unitDiscount, $this->context->currency, new Currency((int)$action->reduction_currency));

                                    $computedProductPackPrice += $unitDiscountConverted;

                                    if ($product['cart_quantity'] == $unitsToRemove) {
                                        unset($cartProductsFilteredBuyX[$actionId][$key]);
                                    } else {
                                        $product['cart_quantity'] -= $unitsToRemove;
                                    }

                                    if ($actionsBuyX[$actionId]->products_nb_each == $unitsRemoved) {
                                        break;
                                    }
                                }
                                unset($product);
                            }
                        }

                        $reductionAmount += $computedProductPackPrice - $action->reduction_amount;
                    }

                    if ($reductionAmount > 0) {
                        $tempCartRule[$tempCartRuleCounter]['reduction_amount'] = (($action->reduction_max_amount > 0 && $reductionAmount > $action->reduction_max_amount) ? $action->reduction_max_amount : $reductionAmount);
                        $tempCartRule[$tempCartRuleCounter]['reduction_currency'] = (int)$action->reduction_currency;
                        $tempCartRule[$tempCartRuleCounter]['reduction_tax'] = (int)$action->reduction_tax;
                    }

                    break;
            }

            $tempCartRuleCounter++;
        }
       /* END OF ACTIONS */

        if (!empty($tempCartRule)) {
            $ruleApplied = false;
            foreach ($tempCartRule as $aTempCartRule) {
                if (!isset($aTempCartRule['duplicate_rule'])) {
                    $aTempCartRule['duplicate_rule'] = 1;
                }

                if ($aTempCartRule['duplicate_rule'] > 0) {
                    $ruleApplied = true;
                    $this->createCartRule($aTempCartRule, $quantityDiscountRule);
                }
            }

            if ($ruleApplied) {
                //We need to force cache cleaning to get rules
                Cache::clean('Cart::getCartRules_'.(int)$this->context->cart->id.'-'.CartRule::FILTER_ACTION_ALL);
                Cache::clean('Cart::getCartRules_'.(int)$this->context->cart->id.'-'.CartRule::FILTER_ACTION_SHIPPING);
                Cache::clean('Cart::getCartRules_'.(int)$this->context->cart->id.'-'.CartRule::FILTER_ACTION_REDUCTION);
                Cache::clean('Cart::getCartRules_'.(int)$this->context->cart->id.'-'.CartRule::FILTER_ACTION_GIFT);
                if (version_compare(_PS_VERSION_, '1.5.4.0', '>=')) {
                    Cache::clean('Cart::getCartRules_'.(int)$this->context->cart->id.'-'.CartRule::FILTER_ACTION_ALL_NOCAP);
                }

                Cache::clean('Cart::getOrderedCartRulesIds_'.$this->context->cart->id.'-'.CartRule::FILTER_ACTION_ALL). '-ids';
                Cache::clean('Cart::getOrderedCartRulesIds_'.$this->context->cart->id.'-'.CartRule::FILTER_ACTION_SHIPPING). '-ids';
                Cache::clean('Cart::getOrderedCartRulesIds_'.$this->context->cart->id.'-'.CartRule::FILTER_ACTION_REDUCTION). '-ids';
                Cache::clean('Cart::getOrderedCartRulesIds_'.$this->context->cart->id.'-'.CartRule::FILTER_ACTION_GIFT). '-ids';

                return true;
            }
        }

        return false;
    }

    public function createCartRule($tempCartRule, $quantityDiscountRule)
    {
        //Before adding it, check if this rule is already applied to prevent simultaneous async calls
        if (!isset($tempCartRule['gift_product'])
            && $this->isAlreadyInCart((int)$this->context->cart->id, (int)$quantityDiscountRule->id_quantity_discount_rule)) {
            return false;
        }

        for ($i = 0; $i < $tempCartRule['duplicate_rule']; $i++) {
            if (!Db::getInstance()->insert('cart_rule', array(
                'id_customer' => (int)$this->context->customer->id,
                'date_from' => $quantityDiscountRule->date_from,
                'date_to' => $quantityDiscountRule->date_to,
                'description' => '',
                'quantity' => '1',
                'quantity_per_user' => '1',
                'priority' => '1',
                'partial_use' => '0',
                'code' => (isset($quantityDiscountRule->code) && $quantityDiscountRule->code) ? $quantityDiscountRule->code : $quantityDiscountRule->code_prefix.Tools::strtoupper(Tools::passwdGen(12)),
                'minimum_amount' => '0',
                'minimum_amount_tax' => '0',
                'minimum_amount_currency' => '0',
                'minimum_amount_shipping' => '0',
                'country_restriction' => '0',
                'carrier_restriction' => '0',
                'group_restriction' => '0',
                'cart_rule_restriction' => '0',
                'product_restriction' => '0',
                'shop_restriction' => '0',
                'free_shipping' => isset($tempCartRule['free_shipping']) ? $tempCartRule['free_shipping'] : '0',
                'reduction_percent' => '0',
                'reduction_amount' => isset($tempCartRule['reduction_amount']) ? $tempCartRule['reduction_amount'] : '0',
                'reduction_tax' => isset($tempCartRule['reduction_tax']) ? $tempCartRule['reduction_tax'] : '0',
                'reduction_currency' => isset($tempCartRule['reduction_currency']) ? $tempCartRule['reduction_currency'] : '0',
                'reduction_product' => '0',
                'gift_product' => isset($tempCartRule['gift_product']) ? $tempCartRule['gift_product'] : '0',
                'gift_product_attribute' => isset($tempCartRule['gift_product_attribute']) ? $tempCartRule['gift_product_attribute'] : '0',
                'highlight' => '0',
                'active' => 1,
                'date_add' => date('Y-m-d H:i:s'),
                'date_upd' => date('Y-m-d H:i:s')
            ))) {
                return false;
            }

            $id = Db::getInstance()->Insert_ID();

            foreach (Language::getLanguages(false) as $lang) {
                if (!Db::getInstance()->insert('cart_rule_lang', array(
                    'id_cart_rule' => (int)$id,
                    'name' => pSQL(isset($quantityDiscountRule->name[$lang['id_lang']]) ? $quantityDiscountRule->name[$lang['id_lang']] : $quantityDiscountRule->name[Configuration::get('PS_LANG_DEFAULT')], false),
                    'id_lang' => (int)$lang['id_lang']
                ))) {
                    return false;
                }
            }

            if ($this->isMultishop()) {
                if (!Db::getInstance()->insert('cart_rule_shop', array(
                    'id_cart_rule' => (int)$id,
                    'id_shop' => (int)Context::getContext()->shop->id
                ))) {
                    return false;
                }
            }

            if (!Db::getInstance()->insert('quantity_discount_rule_cart', array(
                'id_cart' => (int)$this->context->cart->id,
                'id_quantity_discount_rule' => (int)$quantityDiscountRule->id,
                'id_cart_rule' => (int)$id,
                'id_action_type' => isset($tempCartRule['id_action_type']) ? (int)$tempCartRule['id_action_type'] : 0
            ))) {
                return false;
            }

            // Add the cart rule to the cart
            if (!Db::getInstance()->insert('cart_cart_rule', array(
                'id_cart_rule' => (int)$id,
                'id_cart' => (int)$this->context->cart->id
            ))) {
                return false;
            }
        }

        if (isset($tempCartRule['gift_product'])) {
            $this->context->cart->updateQty($tempCartRule['duplicate_rule'], isset($tempCartRule['gift_product']) ? $tempCartRule['gift_product'] : 0, isset($tempCartRule['gift_product_attribute']) ? $tempCartRule['gift_product_attribute'] : 0, false, 'up', 0, null, false);
        }

        return true;
    }

    public static function getQuantityDiscountRulesAtCart($id_cart, $id_quantity_discount_rule = null, $id_cart_rule = null)
    {
        if (!(int)$id_cart || !(int)$id_cart > 0) {
            return false;
        }

        $sql = 'SELECT qdrc.`id_quantity_discount_rule`, qdrc.`id_cart_rule`, qdr.`code`, qdrc.`id_action_type`
            FROM `'._DB_PREFIX_.'quantity_discount_rule_cart` qdrc
            LEFT JOIN `'._DB_PREFIX_.'quantity_discount_rule` qdr ON (qdr.`id_quantity_discount_rule` = qdrc.`id_quantity_discount_rule`)
            WHERE `id_cart` = '.(int)$id_cart.
            ($id_quantity_discount_rule ? ' AND `id_quantity_discount_rule` = '.(int)$id_quantity_discount_rule : '').
            ($id_cart_rule ? ' AND `id_cart_rule` = '.(int)$id_cart_rule : '');

        return Db::getInstance()->executeS($sql);
    }

    public static function getQuantityDiscountRuleByCode($code)
    {
        if (!Validate::isCleanHtml($code)) {
            return false;
        }

        $sql = 'SELECT `id_quantity_discount_rule` FROM `'._DB_PREFIX_.'quantity_discount_rule` WHERE `code` = \''.pSQL($code).'\'';

        return Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($sql);
    }

    public static function getIdCartRuleFromQuantityDiscountRuleFromThisCart($id_quantity_discount_rule, $id_cart)
    {
        if (!(int)$id_quantity_discount_rule || !(int)$id_quantity_discount_rule > 0) {
            return false;
        }

        if (!(int)$id_cart || !(int)$id_cart > 0) {
            return false;
        }

        $sql = 'SELECT qdrc.`id_cart_rule`
            FROM `'._DB_PREFIX_.'quantity_discount_rule_cart` qdrc
            WHERE qdrc.`id_quantity_discount_rule` = '.(int)$id_quantity_discount_rule.' AND `id_cart` = '.(int)$id_cart;

        return Db::getInstance()->getValue($sql);
    }

    public static function cartRuleGeneratedByAQuantityDiscountRuleCode($code)
    {
        if (!Validate::isCleanHtml($code)) {
            return false;
        }

        $sql = 'SELECT qdrc.`id_quantity_discount_rule`, qdrc.`id_cart_rule`, qdr.`code`
            FROM `'._DB_PREFIX_.'quantity_discount_rule_cart` qdrc
            LEFT JOIN `'._DB_PREFIX_.'quantity_discount_rule` qdr ON (qdr.`id_quantity_discount_rule` = qdrc.`id_quantity_discount_rule`)
            LEFT JOIN `'._DB_PREFIX_.'cart_rule` cr ON (qdrc.`id_cart_rule` = cr.`id_cart_rule`)
            WHERE qdr.code <> "" AND cr.code = \''.pSQL($code).'\'';

        return Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($sql);
    }

    public static function cartRuleGeneratedByAQuantityDiscountRuleCodeWithoutCode($code)
    {
        if (!Validate::isCleanHtml($code)) {
            return false;
        }

        $sql = 'SELECT qdrc.`id_quantity_discount_rule`, qdrc.`id_cart_rule`, qdr.`code`
            FROM `'._DB_PREFIX_.'quantity_discount_rule_cart` qdrc
            LEFT JOIN `'._DB_PREFIX_.'quantity_discount_rule` qdr ON (qdr.`id_quantity_discount_rule` = qdrc.`id_quantity_discount_rule`)
            LEFT JOIN `'._DB_PREFIX_.'cart_rule` cr ON (qdrc.`id_cart_rule` = cr.`id_cart_rule`)
            WHERE cr.code = \''.pSQL($code).'\'';

        return Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($sql);
    }

    public static function isQuantityDiscountRule($id_cart_rule)
    {
        if (!(int)$id_cart_rule > 0) {
            return false;
        }

        $sql = 'SELECT qdrc.`id_cart_rule`
            FROM `'._DB_PREFIX_.'quantity_discount_rule_cart` qdrc
            LEFT JOIN `'._DB_PREFIX_.'cart_rule` cr ON (qdrc.`id_cart_rule` = cr.`id_cart_rule`)
            WHERE qdrc.`id_cart_rule` = '.(int)$id_cart_rule;

        return Db::getInstance()->getValue($sql);
    }

    public static function isQuantityDiscountRuleFromThisCart($id_quantity_discount_rule, $id_cart)
    {
        if (!(int)$id_quantity_discount_rule || !(int)$id_quantity_discount_rule > 0) {
            return false;
        }

        if (!(int)$id_cart || !(int)$id_cart > 0) {
            return false;
        }

        $sql = 'SELECT qdrc.`id_quantity_discount_rule`
            FROM `'._DB_PREFIX_.'quantity_discount_rule_cart` qdrc
            LEFT JOIN `'._DB_PREFIX_.'quantity_discount_rule` qdr ON (qdr.`id_quantity_discount_rule` = qdrc.`id_quantity_discount_rule`)
            WHERE qdrc.`id_cart_rule` = '.(int)$id_quantity_discount_rule.' AND `id_cart` = '.(int)$id_cart;

        return Db::getInstance()->getValue($sql);
    }

    public static function getIdCartFruleFromIdQuantityDiscountRuleFromThisCart($id_quantity_discount_rule, $id_cart)
    {
        if (!(int)$id_quantity_discount_rule || !(int)$id_quantity_discount_rule > 0) {
            return false;
        }

        if (!(int)$id_cart || !(int)$id_cart > 0) {
            return false;
        }

        $sql = 'SELECT qdrc.`id_cart_rule`
            FROM `'._DB_PREFIX_.'quantity_discount_rule_cart` qdrc
            LEFT JOIN `'._DB_PREFIX_.'quantity_discount_rule` qdr ON (qdr.`id_quantity_discount_rule` = qdrc.`id_quantity_discount_rule`)
            WHERE qdrc.`id_quantity_discount_rule` = '.(int)$id_quantity_discount_rule.' AND `id_cart` = '.(int)$id_cart;

        return Db::getInstance()->getValue($sql);
    }

    public static function removeQuantityDiscountCartRule($id_cart_rule, $id_cart)
    {
        if (!(int)$id_cart_rule || !(int)$id_cart || !(int)$id_cart_rule > 0 || !(int)$id_cart > 0) {
            return false;
        }

        if (!self::isQuantityDiscountRuleFromThisCart((int)$id_cart_rule, (int)$id_cart)) {
            return false;
        }

        $context = Context::getContext();

        $cartRulesInCart = self::getQuantityDiscountRulesAtCart((int)$id_cart, null, (int)$id_cart_rule);

        foreach ($cartRulesInCart as $cartRuleInCart) {
            if ((int)$cartRuleInCart['id_action_type'] == 1) {
                $sql = "SELECT * FROM `"._DB_PREFIX_."quantity_discount_rule_cart_product` t
                    WHERE `id_cart` = ".(int)$id_cart." AND `id_quantity_discount_rule` =".(int)$cartRuleInCart['id_quantity_discount_rule']."
                    ORDER BY 1";

                $result = Db::getInstance()->executeS($sql);

                foreach ($result as $product) {
                    $context->cart->updateQty((int)$product['quantity'], (int)$product['id_product'], (int)$product['id_product_attribute'], null, 'down', 0, null, false);
                }

                Db::getInstance()->execute(
                    "DELETE FROM `"._DB_PREFIX_."quantity_discount_rule_cart_product`
                    WHERE `id_cart` = ".(int)$id_cart." AND `id_quantity_discount_rule` =".(int)$cartRuleInCart['id_quantity_discount_rule']
                );
            } elseif ((int)$cartRuleInCart['id_action_type'] == 2) {
                $sql = "SELECT * FROM `"._DB_PREFIX_."quantity_discount_rule_cart_product` t
                    WHERE `id_cart` = ".(int)$id_cart." AND `id_quantity_discount_rule` =".(int)$cartRuleInCart['id_quantity_discount_rule']."
                    ORDER BY 1";

                $result = Db::getInstance()->executeS($sql);

                foreach ($result as $product) {
                    $context->cart->updateQty((int)$product['quantity'], (int)$product['id_product'], (int)$product['id_product_attribute'], null, 'up', 0, null, false);
                }

                Db::getInstance()->execute(
                    "DELETE FROM `"._DB_PREFIX_."quantity_discount_rule_cart_product`
                    WHERE `id_cart` = ".(int)$id_cart." AND `id_quantity_discount_rule` =".(int)$cartRuleInCart['id_quantity_discount_rule']
                );
            }
        }

        $cartRule = new CartRule((int)$id_cart_rule);
        if (self::getQuantityDiscountRuleByCode($cartRule->code)) {
            $cartRulesInCart = self::getQuantityDiscountRulesAtCart((int)$id_cart);
            foreach ($cartRulesInCart as $cartRuleInCart) {
                if ($cartRuleInCart['code'] == $cartRule->code) {
                    $cartRule = new CartRule((int)$cartRuleInCart['id_cart_rule']);
                    if (!Db::getInstance()->execute("DELETE FROM `"._DB_PREFIX_."quantity_discount_rule_cart`
                        WHERE `id_cart` = ".(int)$id_cart." AND `id_cart_rule` =".(int)$cartRuleInCart['id_cart_rule'])
                        || !$context->cart->removeCartRule((int)$cartRuleInCart['id_cart_rule'])
                        || !$cartRule->delete()) {
                        return false;
                    }
                }
            }
        } else {
            if (!Db::getInstance()->execute("DELETE FROM `"._DB_PREFIX_."quantity_discount_rule_cart`
                    WHERE `id_cart` = ".(int)$id_cart." AND `id_cart_rule` =".(int)$id_cart_rule)
                || !$context->cart->removeCartRule((int)$id_cart_rule)
                || !$cartRule->delete()) {
                return false;
            }
        }

        return true;
    }

    public function isQuantityDiscountRuleValid($cartRulesCodes = null, $highlight = false)
    {
        if ($this->modules_exceptions) {
            $backtrace = version_compare(PHP_VERSION, '5.3.6', '>=') ? debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS) : debug_backtrace();
            foreach (explode(";", $this->modules_exceptions) as $modules_exception) {
                foreach (array_column($backtrace, 'file') as $element) {
                    if (strpos($element, $modules_exception) !== false) {
                        return false;
                    }
                }
            }
        }

        //Check Date
        $now = date('Y-m-d H:i:s');
        if (strtotime($now) <= strtotime($this->date_from)
            || strtotime($now) >= strtotime($this->date_to)) {
            return false;
        }

        //Check if it's not out of stock
        $times_used = Db::getInstance()->getValue(
            "SELECT count(*)
            FROM "._DB_PREFIX_."orders o
            LEFT JOIN "._DB_PREFIX_."order_cart_rule od ON o.id_order = od.id_order
            LEFT JOIN "._DB_PREFIX_."quantity_discount_rule_order qdro ON od.id_cart_rule = qdro.id_cart_rule
            WHERE qdro.id_quantity_discount_rule = ".(int)$this->id."
            AND ".(int)Configuration::get('PS_OS_ERROR')." != o.current_state"
        );

        if ($this->quantity != 0 && ($times_used >= $this->quantity)) {
            return false;
        }

        if ((int)$this->context->customer->id) {
            $quantityUsed = Db::getInstance()->getValue(
                "SELECT count(*)
                FROM `"._DB_PREFIX_."orders` o
                LEFT JOIN `"._DB_PREFIX_."order_cart_rule` od ON o.`id_order` = od.`id_order`
                LEFT JOIN `"._DB_PREFIX_."quantity_discount_rule_order` qdro ON od.`id_cart_rule` = qdro.`id_cart_rule`
                WHERE o.`id_customer` = ".(int)$this->context->customer->id."
                AND qdro.`id_quantity_discount_rule` = ".(int)$this->id
            );
            if ($quantityUsed + 1 > $this->quantity_per_user) {
                return false;
            }
        }

        if (!$highlight && $this->code && (!$cartRulesCodes || !is_array($cartRulesCodes) || !count($cartRulesCodes) || !in_array($this->code, $cartRulesCodes))) {
            return false;
        }

        return true;
    }

    public function isQuantityDiscountRuleValidForMessages()
    {
        //Check Date
        $now = date('Y-m-d H:i:s');
        if (strtotime($now) <= strtotime($this->date_from)
            || strtotime($now) >= strtotime($this->date_to)) {
            return false;
        }

        //Check if it's not out of stock
        $times_used = Db::getInstance()->getValue(
            "SELECT count(*)
            FROM "._DB_PREFIX_."orders o
            LEFT JOIN "._DB_PREFIX_."order_cart_rule od ON o.id_order = od.id_order
            LEFT JOIN "._DB_PREFIX_."quantity_discount_rule_order qdro ON od.id_cart_rule = qdro.id_cart_rule
            WHERE qdro.id_quantity_discount_rule = ".(int)$this->id."
            AND ".(int)Configuration::get('PS_OS_ERROR')." != o.current_state"
        );

        if ($this->quantity != 0 && ($times_used >= $this->quantity)) {
            return false;
        }

        if ((int)$this->context->customer->id) {
            $quantityUsed = Db::getInstance()->getValue(
                "SELECT count(*)
                FROM `"._DB_PREFIX_."orders` o
                LEFT JOIN `"._DB_PREFIX_."order_cart_rule` od ON o.`id_order` = od.`id_order`
                LEFT JOIN `"._DB_PREFIX_."quantity_discount_rule_order` qdro ON od.`id_cart_rule` = qdro.`id_cart_rule`
                WHERE o.`id_customer` = ".(int)$this->context->customer->id."
                AND qdro.`id_quantity_discount_rule` = ".(int)$this->id
            );
            if ($quantityUsed + 1 > $this->quantity_per_user) {
                return false;
            }
        }

        return true;
    }

    public function validateQuantityDiscountRuleConditions()
    {
        if (!isset($this->context->cart)) {
            return false;
        }

        $cache_key = 'QuantityDiscountRule::validateQuantityDiscountRuleConditions_'.(int)$this->id_quantity_discount_rule;

        if (!Cache::isStored($cache_key)) {
            $groupConditions = $this->getGroups(true);
            if (!$groupConditions) {
                $result = true;
                Cache::store($cache_key, $result);
                return $result;
            }

            foreach ($groupConditions as $groupCondition) {
                $conditions = $groupCondition->getConditions();

                if (!$conditions) {
                    $result = true;
                    Cache::store($cache_key, $result);
                    return $result;
                }

                foreach ($conditions as $condition) {
                    $groupValidationPassed = false;
                    $condition = new QuantityDiscountRuleCondition($condition['id_quantity_discount_rule_condition']);

                    switch ((int)$condition->id_type) {
                        /**
                         * Limit to a single customer
                         *
                         * Check if customer matches the condition customer
                         */
                        case 1:
                            if ((int)$this->context->customer->id == (int)$condition->id_customer) {
                                $groupValidationPassed = true;
                            }

                            break;

                        /**
                         * Customer must be suscribed to newsletter
                         *
                         * Check if customer is/or not subscribed to newsletter
                         */
                        case 2:
                            if ((int)$this->context->customer->id) {
                                $customer = new Customer((int)$this->context->customer->id);
                                if ((int)$customer->newsletter == (int)$condition->customer_newsletter) {
                                    $groupValidationPassed = true;
                                }
                            }

                            break;

                        /**
                         * Customer signed up between a date
                         *
                         * If condition date is by days, substract the number of days to now and check if customer subscribed before this date
                         * If condition date is by interval, check if customer signed up is between these dates
                         */
                        case 3:
                            $time_now = date('Y-m-d');

                            if ((int)$this->context->customer->id) {
                                $customer = new Customer((int)$this->context->customer->id);

                                if ($condition->customer_signedup_date_to == '0000-00-00 00:00:00') {
                                    $condition->customer_signedup_date_to = $time_now;
                                }

                                if (strtotime($condition->customer_signedup_date_from) <= strtotime($customer->date_add) &&
                                    strtotime($condition->customer_signedup_date_to) >= strtotime($customer->date_add)) {
                                    $groupValidationPassed = true;
                                }
                            }

                            break;

                        /**
                         * Customer and orders done
                         *
                         * If condition date is by days, get the orders from this day onwards.
                         * If condition date is by interval, get the orders from this interval.
                         */
                        case 4:
                            $time_now = date('Y-m-d H:i:s');

                            if ((int)$this->context->customer->id) {
                                $orderStates = $condition->getSelectedAssociatedRestrictions('order_state');
                                if ($condition->customer_orders_nb_days > 0) {
                                    $orders = $this->getOrdersIdByDateAndState(date('Y-m-d H:i:s', (strtotime("-".$condition->customer_orders_nb_days." days", strtotime($time_now)))), $time_now, array_column($orderStates['selected'], 'id_order_state'), (int)$this->context->customer->id);
                                } else {
                                    if ($condition->customer_orders_nb_date_to == '0000-00-00 00:00:00') {
                                        $condition->customer_orders_nb_date_to = $time_now;
                                    }

                                    $orders = $this->getOrdersIdByDateAndState($condition->customer_orders_nb_date_from, $condition->customer_orders_nb_date_to, array_column($orderStates['selected'], 'id_order_state'), (int)$this->context->customer->id);
                                }

                                $groupValidationPassed = $this->compareValue((int)$condition->customer_orders_nb_operator, (int)count($orders), (int)$condition->customer_orders_nb);

                                if ($groupValidationPassed) {
                                    $productsTotal = 0;

                                    if ($condition->customer_orders_nb_prod) {
                                        foreach ($orders as $order) {
                                            $order = new Order($order);
                                            $orderProducts = $order->getProducts();

                                            $productsFiltered = $this->filterProducts($orderProducts, $condition, false);
                                            foreach ($productsFiltered as $productFiltered) {
                                                $productsTotal += (int)$productFiltered['product_quantity'];
                                            }
                                        }
                                    }

                                    $groupValidationPassed = $this->compareValue((int)$condition->customer_orders_nb_prod_operator, (int)($productsTotal), (int)$condition->customer_orders_nb_prod);
                                }
                            }

                            break;

                        /**
                         * Customer and amount spent
                         *
                         * If condition date is by days, get the orders from this day onwards.
                         * If condition date is by interval, get the orders from this interval.
                         *
                         * Acumulate amount and convert to currency
                         */
                        case 5:
                            if ((int)$this->context->customer->id && (int)$condition->customer_orders_amount_orders > 0) {
                                $time_now = date('Y-m-d H:i:s');
                                $totalAmount = 0;
                                $orders = array();
                                $orderStates = $condition->getSelectedAssociatedRestrictions('order_state');
                                if ($condition->customer_orders_amount_days > 0) {
                                    $orders = $this->getOrdersIdByDateAndState(date('Y-m-d H:i:s', (strtotime("-".$condition->customer_orders_amount_days." days", strtotime($time_now)))), $time_now, array_column($orderStates['selected'], 'id_order_state'), (int)$this->context->customer->id);
                                } elseif ($condition->customer_orders_amount_date_to != '0000-00-00 00:00:00'
                                    || $condition->customer_orders_amount_date_from != '0000-00-00 00:00:00') {
                                    if ($condition->customer_orders_amount_date_to == '0000-00-00 00:00:00') {
                                        $condition->customer_orders_amount_date_to = $time_now;
                                    }
                                    $orders = $this->getOrdersIdByDateAndState($condition->customer_orders_amount_date_from, $condition->customer_orders_amount_date_to, array_column($orderStates['selected'], 'id_order_state'), (int)$this->context->customer->id);
                                } elseif ($condition->customer_orders_amount_interval) {
                                    switch ($condition->customer_orders_amount_interval) {
                                        case '4':
                                            $orders = $this->getOrdersIdByDateAndState(date('Y-m-d', strtotime('first day of last month')), date('Y-m-d', strtotime('last day of last month')), array_column($orderStates['selected'], 'id_order_state'), (int)$this->context->customer->id);
                                            break;
                                    }
                                } else {
                                    // Condition not configured correctly
                                    break;
                                }

                                if ((int)$condition->customer_orders_amount_orders && (count($orders) > (int)$condition->customer_orders_amount_orders)) {
                                    $orders = array_slice($orders, -(int)$condition->customer_orders_amount_orders, (int)$condition->customer_orders_amount_orders, true);
                                }

                                foreach ($orders as $order) {
                                    $order = new Order((int)$order);
                                    $totalOrder = 0;

                                    if ((int)$condition->customer_orders_amount_tax) {
                                        $totalOrder += $order->total_products_wt;
                                        if ((int)$condition->customer_orders_amount_shipping) {
                                            $totalOrder += $order->total_shipping_tax_incl;
                                        }
                                    } else {
                                        $totalOrder += $order->total_products;
                                        if ((int)$condition->customer_orders_amount_shipping) {
                                            $totalOrder += $order->total_shipping_tax_excl;
                                        }
                                    }

                                    /** Remove discounts */
                                    if (!(int)$condition->customer_orders_amount_discount) {
                                        if ((int)$condition->customer_orders_amount_tax) {
                                            $totalOrder -= $order->total_discounts_tax_incl;
                                        } else {
                                            $totalOrder -= $order->total_discounts_tax_excl;
                                        }
                                    }

                                    // We convert amount to default currency using its own conversion rate
                                    $totalAmount += self::convertPriceWithConversionRate($totalOrder, $order->conversion_rate, true);
                                }

                                if ((int)$condition->customer_orders_amount_currency != Configuration::get('PS_CURRENCY_DEFAULT')) {
                                    $totalAmount = self::convertPriceFull($totalAmount, new Currency((int)Configuration::get('PS_CURRENCY_DEFAULT')), new Currency((int)$condition->customer_orders_amount_currency), false);
                                }

                                $groupValidationPassed = $this->compareValue((int)$condition->customer_orders_amount_operator, $totalAmount, $condition->customer_orders_amount);
                            }

                            break;

                        /**
                         * Only for first order
                         *
                         * Check if it's/or not the customer's first order
                         *
                         */
                        case 6:
                            if ((int)$this->context->customer->id) {
                                $firstOrder = Db::getInstance()->getValue(
                                    'SELECT COUNT(`id_customer`) as nb
                                    FROM `'._DB_PREFIX_.'orders`
                                    WHERE `id_customer` = '.(int)$this->context->customer->id
                                );

                                if (($condition->customer_first_order && !$firstOrder) || (!$condition->customer_first_order && $firstOrder)) {
                                    $groupValidationPassed = true;
                                }
                            }

                            break;

                        /**
                         * Total cart amount
                         *
                         * Get the cart amount or only the amount from products without special price.
                         * Add shipping cost. Substract gift products.
                         *
                         */
                        case 8:
                            if ($condition->cart_amount > 0) {
                                $cartAmount = $condition->cart_amount;
                                if ((int)$condition->cart_amount_currency != $this->context->currency->id) {
                                    $cartAmount = self::convertPriceFull($cartAmount, new Currency((int)$condition->cart_amount_currency), $this->context->currency, false);
                                }

                                /** Get the cart amount or only the amount from products without special price. */
                                if (!(int)$condition->apply_discount_to_special) {
                                    $cartProducts = $this->context->cart->getProducts();
                                    $cartTotal = 0;
                                    foreach ($cartProducts as $cartProduct) {
                                        if (!Product::getPriceStatic($cartProduct['id_product'], (int)$condition->cart_amount_tax, (isset($cartProduct['id_product_attribute']) ? (int)$cartProduct['id_product_attribute'] : null), 6, null, true, true, $cartProduct['cart_quantity']) > 0) {
                                            $cartTotal += Product::getPriceStatic($cartProduct['id_product'], (int)$condition->cart_amount_tax, (isset($cartProduct['id_product_attribute']) ? (int)$cartProduct['id_product_attribute'] : null), 6, null, false, true, $cartProduct['cart_quantity'])*$cartProduct['cart_quantity'];
                                        }
                                    }
                                } else {
                                    $cartTotal = $this->context->cart->getOrderTotal((int)$condition->cart_amount_tax, Cart::ONLY_PRODUCTS);
                                }

                                /** Add shipping cost */
                                if ((int)$condition->cart_amount_shipping) {
                                    $cartTotal += $this->context->cart->getOrderTotal($condition->cart_amount_tax, Cart::ONLY_SHIPPING);
                                }

                                /** Remove discounts */
                                if (!(int)$condition->cart_amount_discount) {
                                    $cartTotal -= $this->context->cart->getOrderTotal($condition->cart_amount_tax, Cart::ONLY_DISCOUNTS);
                                }

                                $cartTotal -= $this->getGiftProductsValue($condition->cart_amount_tax);

                                $groupValidationPassed = $this->compareValue((int)$condition->cart_amount_operator, $cartTotal, $cartAmount);
                            }

                            break;

                        /**
                         * Cart weight
                         *
                         * Check the cart weight
                         *
                         */
                        case 9:
                            if ($condition->cart_weight > 0) {
                                $cartWeight = $this->context->cart->getTotalWeight();

                                $groupValidationPassed = $this->compareValue((int)$condition->cart_weight_operator, $cartWeight, $condition->cart_weight);
                            }

                            break;

                        /**
                         * Products in the cart
                         *
                         * Get all products from the cart. Remove those which don't meet the filters selected at the condition.
                         *
                         */
                        case 10:
                            $cartProducts = $this->context->cart->getProducts();
                            $cartProductsFiltered = $this->filterProducts($cartProducts, $condition);

                            if (!$cartProductsFiltered) {
                                break;
                            }

                            /**
                             * Check if all products from the cart must met the condition
                             */
                            if ((int)$condition->products_all_met && (count($cartProducts) != count($cartProductsFiltered))) {
                                break;
                            }

                            /** Quantity of selected products in cart */
                            if ((int)$condition->products_nb) {
                                $condition->group_products_by = 'all';
                                $productsGrouped = $this->groupProducts((int)$this->context->cart->id, $cartProductsFiltered, $condition);

                                $groupValidationPassed |= $this->compareValue((int)$condition->products_nb_operator, (int)$productsGrouped[0]['cart_quantity'], (int)$condition->products_nb);
                                if (!$groupValidationPassed) {
                                    break;
                                }
                            } else {
                                $groupValidationPassed = true;
                            }

                            /** Number of different products from selected products in cart */
                            if ((int)$condition->products_nb_dif) {
                                $condition->group_products_by = 'product';
                                $productsGrouped = $this->groupProducts((int)$this->context->cart->id, $cartProductsFiltered, $condition);

                                $groupValidationPassed &= $this->compareValue((int)$condition->products_nb_dif_operator, (int)count($productsGrouped), (int)$condition->products_nb_dif);
                            }

                            /** Amount of selected products in cart */
                            if ((int)$condition->products_amount) {
                                $cartAmount = 0;

                                foreach ($cartProductsFiltered as $cartProductFiltered) {
                                    $cartAmount += Product::getPriceStatic($cartProductFiltered['id_product'], (int)$condition->products_amount_tax, (isset($cartProductFiltered['id_product_attribute']) ? (int)$cartProductFiltered['id_product_attribute'] : null), 6, null, false, true, $cartProductFiltered['cart_quantity'])*$cartProductFiltered['cart_quantity'];
                                }

                                if ((int)$condition->products_amount_currency != $this->context->currency->id) {
                                    $conditionProductsAmount = self::convertPriceFull($condition->products_amount, new Currency((int)$condition->products_amount_currency), $this->context->currency, false);
                                } else {
                                    $conditionProductsAmount = $condition->products_amount;
                                }

                                $groupValidationPassed &= $this->compareValue((int)$condition->products_operator, $cartAmount, $conditionProductsAmount);
                            }

                            /** Number of products from the same category in cart */
                            if ((int)$condition->products_nb_dif_cat) {
                                $condition->group_products_by = 'category';
                                $productsGrouped = $this->groupProducts($this->context->cart->id, $cartProductsFiltered, $condition);

                                $groupCategoryValidationPassed = false;
                                foreach ($productsGrouped as $productGrouped) {
                                    $groupCategoryValidationPassed |= $this->compareValue((int)$condition->products_nb_dif_cat_operator, (int)$productGrouped['cart_quantity'], (int)$condition->products_nb_dif_cat);
                                }

                                $groupValidationPassed &= $groupCategoryValidationPassed;
                            }

                            break;

                        /**
                         * Delivery country
                         *
                         * Get the order address delivery and check if it matches condition country
                         */
                        case 11:
                            if ((int)$this->context->cart->id_address_delivery) {
                                $address = new Address($this->context->cart->id_address_delivery);
                                $conditionCountry = $condition->getSelectedAssociatedRestrictions('country');

                                if (count($conditionCountry['selected'])) {
                                    if (in_array($address->id_country, array_column($conditionCountry['selected'], 'id_country'))) {
                                        $groupValidationPassed = true;
                                        break;
                                    }
                                }
                            }

                            break;

                        /**
                         * Carrier
                         *
                         * Check if carrier matches with selected
                         */
                        case 12:
                            if ($this->context->cart->id_carrier) {
                                $carrier = new Carrier((int)$this->context->cart->id_carrier);
                                $conditionCarriers = $condition->getSelectedAssociatedRestrictions('carrier');

                                if (count($conditionCarriers['selected'])) {
                                    if (in_array($carrier->id_reference, array_column($conditionCarriers['selected'], 'id_carrier'))) {
                                        $groupValidationPassed = true;
                                    }
                                }
                            }

                            break;

                        /**
                         * Group selection
                         *
                         * Check if customer belongs to selected groups
                         */
                        case 13:
                            $conditionGroups = $condition->getSelectedAssociatedRestrictions('group');
                            if (count($conditionGroups['selected'])) {
                                if ((int)$this->context->customer->id && $condition->customer_default_group) {
                                    $customer = new Customer((int)$this->context->customer->id);
                                    if (in_array((int)$customer->id_default_group, array_column($conditionGroups['selected'], 'id_group'))) {
                                        $groupValidationPassed = true;
                                        break;
                                    }
                                } else {
                                    $customerGroups = Customer::getGroupsStatic((int)$this->context->customer->id);
                                    foreach ($customerGroups as $customerGroup) {
                                        if (in_array($customerGroup, array_column($conditionGroups['selected'], 'id_group'))) {
                                            $groupValidationPassed = true;
                                            break;
                                        }
                                    }
                                }
                            }


                            break;

                        /**
                         * Shop selection
                         *
                         * Check if shop belongs to selected shops
                         */
                        case 14:
                            $conditionShops = $condition->getSelectedAssociatedRestrictions('shop');

                            if (count($conditionShops['selected'])) {
                                if (in_array($this->context->shop->id, array_column($conditionShops['selected'], 'id_shop'))) {
                                    $groupValidationPassed = true;
                                    break;
                                }
                            }

                            break;

                        /**
                         * Delivery zone
                         *
                         * Check if delivery zone matches with selected
                         */
                        case 18:
                            if ($this->context->cart->id_address_delivery) {
                                $id_zone = Address::getZoneById($this->context->cart->id_address_delivery);
                                $conditionZones = $condition->getSelectedAssociatedRestrictions('zone');

                                if (count($conditionZones['selected'])) {
                                    if (in_array($id_zone, array_column($conditionZones['selected'], 'id_zone'))) {
                                        $groupValidationPassed = true;
                                    }
                                }
                            }

                            break;

                        /**
                         * Membership
                         *
                         * Compare number of days of membership with defined
                         */
                        case 19:
                            if ((int)$this->context->customer->id) {
                                $now = new DateTime(date('Y-m-d H:i:s'));
                                $customer = new Customer((int)$this->context->customer->id);
                                $diff = $now->diff(new DateTime(date($customer->date_add)))->format("%a");

                                $groupValidationPassed = $this->compareValue((int)$condition->customer_membership_operator, (int)$diff, (int)$condition->customer_membership);
                            }

                            break;

                        /**
                         * Birthday
                         *
                         * Get day/month of customer's birthday and compare with current day
                         */
                        case 20:
                            if ((int)$this->context->customer->id) {
                                $now = date('m-d');
                                $customer = new Customer((int)$this->context->customer->id);

                                if ($condition->customer_newsletter && $now == date('m-d', strtotime($customer->birthday))) {
                                    $groupValidationPassed = true;
                                } elseif (!$condition->customer_newsletter && $now != date('m-d', strtotime($customer->birthday))) {
                                    $groupValidationPassed = true;
                                }
                            }

                            break;

                        /*
                         * By gender
                         */
                        case 21:
                            if ((int)$this->context->customer->id) {
                                $customer = new Customer((int)$this->context->customer->id);
                                $conditionGenders = $condition->getSelectedAssociatedRestrictions('gender');

                                if ($customer->id_gender) {
                                    if (count($conditionGenders['selected'])) {
                                        if (in_array($customer->id_gender, array_column($conditionGenders['selected'], 'id_gender'))) {
                                            $groupValidationPassed = true;
                                        }
                                    }
                                }
                            }

                            break;

                        /*
                         * By currency
                         */
                        case 22:
                            $conditionCurrencies = $condition->getSelectedAssociatedRestrictions('currency');

                            if (count($conditionCurrencies['selected'])) {
                                if (in_array($this->context->cart->id_currency, array_column($conditionCurrencies['selected'], 'id_currency'))) {
                                    $groupValidationPassed = true;
                                }
                            }

                            break;

                        /**
                         * Customer age
                         *
                         * Get day/month of customer's birthday and compare if it's between defined age
                         */
                        case 23:
                            if ((int)$this->context->customer->id) {
                                $now = date('m-d');
                                $customer = new Customer((int)$this->context->customer->id);
                                $birthDate = $customer->birthday;
                                if ($birthDate && $birthDate != '0000-00-00') {
                                    $age = date_diff(date_create($birthDate), date_create('now'))->y;
                                    if ($age >= $condition->customer_years_from && $age <= $condition->customer_years_to) {
                                        $groupValidationPassed = true;
                                    }
                                }
                            }

                            break;

                        /**
                         * Delivery state
                         *
                         * Check if delivery state matches with selected
                         */
                        case 24:
                            if ((int)$this->context->cart->id_address_delivery) {
                                $address = new Address($this->context->cart->id_address_delivery);
                                $conditionState = $condition->getSelectedAssociatedRestrictions('state');

                                if (count($conditionState['selected'])) {
                                    if (in_array($address->id_state, array_column($conditionState['selected'], 'id_state'))) {
                                        $groupValidationPassed = true;
                                        break;
                                    }
                                }
                            }

                            break;

                        /**
                         * Customer has vat
                         *
                         * Get tax type for a product. If is higher than 0, customer has VAT. Please note that we take into consideration
                         * ID address, and that if one product has VAT we suppose that all products have VAT
                         */
                        case 25:
                            foreach ($this->context->cart->getProducts() as $product) {
                                if (Configuration::get('PS_TAX_ADDRESS_TYPE') == 'id_address_invoice') {
                                    $address_id = (int)$this->context->cart->id_address_invoice;
                                } else {
                                    $address_id = (int)$product['id_address_delivery'];
                                } // Get delivery address of the product from the cart

                                if (!Address::addressExists($address_id)) {
                                    $address_id = null;
                                }

                                if (Tax::getProductTaxRate((int)$product['id_product'], (int)$address_id)) {
                                    $groupValidationPassed = true;
                                    break;
                                }
                            }

                            break;

                        /*
                         * Day of the week
                         *
                         * Get today and check if it's valid
                         */
                        case 26:
                            $schedule = Tools::jsonDecode($condition->schedule);
                            $dayOfWeek = date('w') - 1;
                            if ($dayOfWeek < 0) {
                                $dayOfWeek = 6;
                            }

                            if (is_array($schedule)) {
                                if (is_object($schedule[$dayOfWeek]) && $schedule[$dayOfWeek]->isActive === true) {
                                    if ($schedule[$dayOfWeek]->timeFrom <= date('H:i') && $schedule[$dayOfWeek]->timeTill > date('H:i')) {
                                        $groupValidationPassed = true;
                                    }
                                }
                            }

                            break;

                        /*
                         * URL string
                         */
                        case 27:
                            $qdp_url_string = Tools::jsonDecode(Context::getContext()->cookie->qdp_url_string, true);
                            if (isset($qdp_url_string[(int)$this->id_quantity_discount_rule])) {
                                $groupValidationPassed = true;
                            }

                            break;
                    }

                    if (!$groupValidationPassed) {
                        break;
                    }
                }

                /**
                 * Logical OR between each group of conditions
                 *
                 * If any of the group condition is valid, then rule must be applied
                 */
                if ($groupValidationPassed) {
                    $result = true;
                    Cache::store($cache_key, $result);
                    return $result;
                }
            }
        } else {
            return Cache::retrieve($cache_key);
        }

        $result = false;
        Cache::store($cache_key, $result);

        return $result;
    }

    public function validateCartRuleForMessages($id_product = null, $validateProducts = true)
    {
        $groupConditions = $this->getGroups(true);

        foreach ($groupConditions as $groupCondition) {
            $groupValidationPassed = false;
            $anotherChance = false;
            $previousChance = false;

            $conditions = $groupCondition->getConditions();

            if (!$conditions) {
                continue;
            }

            foreach ($conditions as $condition) {
                $groupValidationPassed = false;
                $condition = new QuantityDiscountRuleCondition($condition['id_quantity_discount_rule_condition']);

                switch ((int)$condition->id_type) {
                    /**
                     * Limit to a single customer
                     *
                     * Check if customer matches the condition customer
                     */
                    case 1:
                        if ((int)$this->context->customer->id == (int)$condition->id_customer) {
                            $groupValidationPassed = true;
                        }

                        break;

                    /**
                     * Customer must be suscribed to newsletter
                     *
                     * Check if customer is/or not subscribed to newsletter
                     */
                    case 2:
                        if ((int)$this->context->customer->id) {
                            $customer = new Customer((int)$this->context->customer->id);
                            if ((int)$customer->newsletter == (int)$condition->customer_newsletter) {
                                $groupValidationPassed = true;
                            }
                        }

                        break;

                    /**
                     * Customer signed up between a date
                     *
                     * If condition date is by days, substract the number of days to now and check if customer subscribed before this date
                     * If condition date is by interval, check if customer signed up is between these dates
                     */
                    case 3:
                        $time_now = date('Y-m-d');

                        if ((int)$this->context->customer->id) {
                            $customer = new Customer((int)$this->context->customer->id);

                            if ($condition->customer_signedup_date_to == '0000-00-00 00:00:00') {
                                $condition->customer_signedup_date_to = $time_now;
                            }

                            if (strtotime($condition->customer_signedup_date_from) <= strtotime($customer->date_add) &&
                                strtotime($condition->customer_signedup_date_to) >= strtotime($customer->date_add)) {
                                $groupValidationPassed = true;
                            }
                        }

                        break;

                    /**
                     * Customer and orders done
                     *
                     * If condition date is by days, get the orders from this day onwards.
                     * If condition date is by interval, get the orders from this interval.
                     */
                    case 4:
                        $time_now = date('Y-m-d H:i:s');

                        if ((int)$this->context->customer->id) {
                            $orderStates = $condition->getSelectedAssociatedRestrictions('order_state');
                            if ($condition->customer_orders_nb_days > 0) {
                                $orders = $this->getOrdersIdByDateAndState(date('Y-m-d H:i:s', (strtotime("-".$condition->customer_orders_nb_days." days", strtotime($time_now)))), $time_now, array_column($orderStates['selected'], 'id_order_state'), (int)$this->context->customer->id);
                            } else {
                                if ($condition->customer_orders_nb_date_to == '0000-00-00 00:00:00') {
                                    $condition->customer_orders_nb_date_to = $time_now;
                                }

                                $orders = $this->getOrdersIdByDateAndState($condition->customer_orders_nb_date_from, $condition->customer_orders_nb_date_to, array_column($orderStates['selected'], 'id_order_state'), (int)$this->context->customer->id);
                            }

                            $groupValidationPassed = $this->compareValue((int)$condition->customer_orders_nb_operator, (int)count($orders), (int)$condition->customer_orders_nb);

                            if ($groupValidationPassed) {
                                $productsTotal = 0;

                                if ($condition->filter_by_product
                                    || $condition->filter_by_category
                                    || $condition->filter_by_attribute
                                    || $condition->filter_by_supplier
                                    || $condition->filter_by_manufacturer) {
                                    foreach ($orders as $order) {
                                        $order = new Order($order);
                                        $orderProducts = $order->getProducts();

                                        if ($productsFiltered = $this->filterProducts($orderProducts, $condition)) {
                                            foreach ($productsFiltered as $productFiltered) {
                                                $productsTotal += (int)$productFiltered['product_quantity'];
                                            }
                                        }
                                    }
                                }

                                $groupValidationPassed = $this->compareValue((int)$condition->customer_orders_nb_prod_operator, (int)($productsTotal), (int)$condition->customer_orders_nb_prod);
                            }
                        }

                        break;

                    /**
                     * Customer and amount spent
                     *
                     * If condition date is by days, get the orders from this day onwards.
                     * If condition date is by interval, get the orders from this interval.
                     *
                     * Acumulate amount and convert to currency
                     */
                    case 5:
                        if ((int)$this->context->customer->id && (int)$condition->customer_orders_amount_orders > 0) {
                            $time_now = date('Y-m-d H:i:s');
                            $totalAmount = 0;
                            $orders = array();
                            $orderStates = $condition->getSelectedAssociatedRestrictions('order_state');
                            if ($condition->customer_orders_amount_days > 0) {
                                $orders = $this->getOrdersIdByDateAndState(date('Y-m-d H:i:s', (strtotime("-".$condition->customer_orders_amount_days." days", strtotime($time_now)))), $time_now, array_column($orderStates['selected'], 'id_order_state'), (int)$this->context->customer->id);
                            } else {
                                if ($condition->customer_orders_amount_date_to == '0000-00-00 00:00:00') {
                                    $condition->customer_orders_amount_date_to = $time_now;
                                }
                                $orders = $this->getOrdersIdByDateAndState($condition->customer_orders_amount_date_from, $condition->customer_orders_amount_date_to, array_column($orderStates['selected'], 'id_order_state'), (int)$this->context->customer->id);
                            }

                            if ((int)$condition->customer_orders_amount_orders && (count($orders) > (int)$condition->customer_orders_amount_orders)) {
                                $orders = array_slice($orders, -(int)$condition->customer_orders_amount_orders, (int)$condition->customer_orders_amount_orders, true);
                            }

                            foreach ($orders as $order) {
                                $order = new Order((int)$order);
                                $totalOrder = 0;

                                if ((int)$condition->customer_orders_amount_tax) {
                                    $totalOrder += $order->total_products_wt;
                                    if ((int)$condition->customer_orders_amount_shipping) {
                                        $totalOrder += $order->total_shipping_tax_incl;
                                    }
                                } else {
                                    $totalOrder += $order->total_products;
                                    if ((int)$condition->customer_orders_amount_shipping) {
                                        $totalOrder += $order->total_shipping_tax_excl;
                                    }
                                }

                                /** Remove discounts */
                                if (!(int)$condition->customer_orders_amount_discount) {
                                    if ((int)$condition->customer_orders_amount_tax) {
                                        $totalOrder -= $order->total_discounts_tax_incl;
                                    } else {
                                        $totalOrder -= $order->total_discounts_tax_excl;
                                    }
                                }

                                // We convert amount to default currency using its own conversion rate
                                $totalAmount += self::convertPriceWithConversionRate($totalOrder, $order->conversion_rate, true);
                            }

                            if ((int)$condition->customer_orders_amount_currency != Configuration::get('PS_CURRENCY_DEFAULT')) {
                                $totalAmount = self::convertPriceFull($totalAmount, new Currency((int)Configuration::get('PS_CURRENCY_DEFAULT')), new Currency((int)$condition->customer_orders_amount_currency), false);
                            }

                            $groupValidationPassed = $this->compareValue((int)$condition->customer_orders_amount_operator, $totalAmount, $condition->customer_orders_amount);
                        }

                        break;

                    /**
                     * Only for first order
                     *
                     * Check if it's/or not the customer's first order
                     *
                     */
                    case 6:
                        if ((int)$this->context->customer->id) {
                            $firstOrder = Db::getInstance()->getValue(
                                'SELECT COUNT(`id_customer`) as nb
                                FROM `'._DB_PREFIX_.'orders`
                                WHERE `id_customer` = '.(int)$this->context->customer->id
                            );

                            if (($condition->customer_first_order && !$firstOrder) || (!$condition->customer_first_order && $firstOrder)) {
                                $groupValidationPassed = true;
                            }
                        } else {
                            $groupValidationPassed = true;
                        }

                        break;

                    case 8:
                    case 9:
                        $groupValidationPassed = true;
                        break;
                    case 11:
                        if ((int)$this->context->cart->id_address_delivery) {
                            $address = new Address($this->context->cart->id_address_delivery);
                            $conditionCountry = $condition->getSelectedAssociatedRestrictions('country');

                            if (count($conditionCountry['selected'])) {
                                if (in_array($address->id_country, array_column($conditionCountry['selected'], 'id_country'))) {
                                    $groupValidationPassed = true;
                                    break;
                                }
                            }
                        }
                        break;

                    case 12:
                        $groupValidationPassed = true;
                        break;

                    case 18:
                        if ($this->context->cart->id_address_delivery) {
                            $id_zone = Address::getZoneById($this->context->cart->id_address_delivery);
                            $conditionZones = $condition->getSelectedAssociatedRestrictions('zone');

                            if (count($conditionZones['selected'])) {
                                if (in_array($id_zone, array_column($conditionZones['selected'], 'id_zone'))) {
                                    $groupValidationPassed = true;
                                    break;
                                }
                            }
                        }

                        break;

                    case 10:
                        if (!$validateProducts) {
                            $groupValidationPassed = true;
                            break;
                        }

                        $id_product = isset($id_product) ? $id_product : Tools::getValue('id_product');
                        if (isset($id_product) && $this->productIsInFilter($id_product, $condition)) {
                            $groupValidationPassed = true;
                            $previousChance = true;
                        } else {
                            $anotherChance = true;
                        }

                        break;

                    /**
                     * Group selection
                     *
                     * Check if customer belongs to selected groups
                     */
                    case 13:
                        $conditionGroups = $condition->getSelectedAssociatedRestrictions('group');
                        if ((int)$this->context->customer->id && $condition->customer_default_group) {
                            $customer = new Customer((int)$this->context->customer->id);
                            if (count($conditionGroups['selected'])) {
                                if (in_array((int)$customer->id_default_group, array_column($conditionGroups['selected'], 'id_group'))) {
                                    $groupValidationPassed = true;
                                    break;
                                }
                            }
                        } else {
                            $customerGroups = Customer::getGroupsStatic((int)$this->context->customer->id);
                            if (count($conditionGroups['selected'])) {
                                foreach ($customerGroups as $customerGroup) {
                                    if (in_array($customerGroup, array_column($conditionGroups['selected'], 'id_group'))) {
                                        $groupValidationPassed = true;
                                        break;
                                    }
                                }
                            }
                        }

                        break;

                    /**
                     * Shop selection
                     *
                     * Check if shop belongs to selected shops
                     */
                    case 14:
                        $conditionShops = $condition->getSelectedAssociatedRestrictions('shop');

                        if (count($conditionShops['selected'])) {
                            if (in_array($this->context->shop->id, array_column($conditionShops['selected'], 'id_shop'))) {
                                $groupValidationPassed = true;
                                break;
                            }
                        }

                        break;

                    /**
                     * Membership
                     *
                     * Compare number of days of membership with defined
                     */
                    case 19:
                        if ((int)$this->context->customer->id) {
                            $now = new DateTime(date('Y-m-d H:i:s'));
                            $customer = new Customer((int)$this->context->customer->id);
                            $diff = $now->diff(new DateTime(date($customer->date_add)))->format("%a");

                            $groupValidationPassed = $this->compareValue((int)$condition->customer_membership_operator, (int)$diff, (int)$condition->customer_membership);
                        }

                        break;

                    /**
                     * Birthday
                     *
                     * Get day/month of customer's birthday and compare with current day
                     */
                    case 20:
                        if ((int)$this->context->customer->id) {
                            $now = date('m-d');
                            $customer = new Customer((int)$this->context->customer->id);

                            if ($now == date('m-d', strtotime($customer->birthday))) {
                                $groupValidationPassed = true;
                            }
                        }

                        break;

                    /*
                     * By gender
                     */
                    case 21:
                        if ((int)$this->context->customer->id) {
                            $customer = new Customer((int)$this->context->customer->id);
                            $conditionGenders = $condition->getSelectedAssociatedRestrictions('gender');

                            if ($customer->id_gender) {
                                if (count($conditionGenders['selected'])) {
                                    if (in_array($customer->id_gender, array_column($conditionGenders['selected'], 'id_gender'))) {
                                        $groupValidationPassed = true;
                                    }
                                }
                            }
                        }

                        break;

                    /*
                     * By currency
                     */
                    case 22:
                        $conditionCurrencies = $condition->getSelectedAssociatedRestrictions('currency');

                        if (count($conditionCurrencies['selected'])) {
                            if (in_array($this->context->cart->id_currency, array_column($conditionCurrencies['selected'], 'id_currency'))) {
                                $groupValidationPassed = true;
                            }
                        }

                        break;

                    /**
                     * Customer age
                     */
                    case 23:
                        if ((int)$this->context->customer->id) {
                            $now = date('m-d');
                            $customer = new Customer((int)$this->context->customer->id);
                            $birthDate = $customer->birthday;
                            if ($birthDate && $birthDate != '0000-00-00') {
                                $age = date_diff(date_create($birthDate), date_create('now'))->y;
                                if ($age >= $condition->customer_years_from && $age <= $condition->customer_years_to) {
                                    $groupValidationPassed = true;
                                }
                            }
                        }

                        break;

                    /**
                     * Customer has vat
                     */
                    case 25:
                        foreach ($this->context->cart->getProducts() as $product) {
                            if (Configuration::get('PS_TAX_ADDRESS_TYPE') == 'id_address_invoice') {
                                $address_id = (int)$this->context->cart->id_address_invoice;
                            } else {
                                $address_id = (int)$product['id_address_delivery'];
                            } // Get delivery address of the product from the cart

                            if (!Address::addressExists($address_id)) {
                                $address_id = null;
                            }

                            if (Tax::getProductTaxRate((int)$product['id_product'], (int)$address_id)) {
                                $groupValidationPassed = true;
                                break;
                            }
                        }

                        break;

                    /*
                     * Day of the week
                     *
                     * Get today and check if it's valid
                     */
                    case 26:
                        switch (date('w')) {
                            case 1:
                                if ($condition->monday) {
                                    $groupValidationPassed = true;
                                }

                                break;

                            case 2:
                                if ($condition->tuesday) {
                                    $groupValidationPassed = true;
                                }

                                break;

                            case 3:
                                if ($condition->wednesday) {
                                    $groupValidationPassed = true;
                                }

                                break;

                            case 4:
                                if ($condition->thursday) {
                                    $groupValidationPassed = true;
                                }

                                break;

                            case 5:
                                if ($condition->friday) {
                                    $groupValidationPassed = true;
                                }

                                break;

                            case 6:
                                if ($condition->saturday) {
                                    $groupValidationPassed = true;
                                }

                                break;

                            case 0:
                                if ($condition->sunday) {
                                    $groupValidationPassed = true;
                                }

                                break;
                        }
                }

                if (!$groupValidationPassed && !$anotherChance) {
                    break;
                }
            }

            if (!$groupValidationPassed && count($conditions) > 0 && !$anotherChance) {
                return false;
            }

            if ($groupValidationPassed && $previousChance) {
                return true;
            }
        }

        if ($validateProducts) {
            $actions = $this->getActions(true);

            foreach ($actions as $action) {
                switch ((int)$action->id_type) {
                    case 6:
                    case 7:
                    case 8:
                    case 12:
                    case 13:
                    case 14:
                    case 15:
                    case 16:
                    case 17:
                    case 18:
                    case 19:
                    case 20:
                    case 21:
                    case 22:
                    case 23:
                    case 27:
                    case 28:
                    case 29:
                    case 31:
                    case 32:
                    case 33:
                    case 34:
                    case 100:
                    case 101:
                    case 102:
                    case 107:
                        $id_product = isset($id_product) ? $id_product : Tools::getValue('id_product');
                        if (isset($id_product) && $this->productIsInFilter($id_product, $action)) {
                            return true;
                        }

                        break;

                    case 30:
                    case 35:
                    case 103:
                        $id_product = isset($id_product) ? $id_product : Tools::getValue('id_product');
                        if (isset($id_product) && $this->productIsInFilter($id_product, $action, true)) {
                            return true;
                        }

                        break;

                    case 104:
                    case 105:
                    case 106:
                        return false;

                    default:
                        if ($anotherChance) {
                            return false;
                        }

                        return true;

                        break;
                }
            }

            return false;
        }

        return true;
    }

    public function getDiscountedAmount($action, $product, $isPercentageAction)
    {
        //$cache_key = 'QuantityDiscountRule::getDiscountedAmount_'.(int)$product['id_product'].'_'.(int)$action->reduction_percent_tax.'_'.(bool)$action->apply_discount_to_regular_price.'_'.(int)$action->reduction_tax;

        //if (!Cache::isStored($cache_key)) {

        $virtual_context = Context::getContext()->cloneContext();
        $specific_price_output = null;
        if ($virtual_context->shop->id != $product['id_shop']) {
            $virtual_context->shop = new Shop((int)$product['id_shop']);
        }

        if ($isPercentageAction) {
            if (version_compare(_PS_VERSION_, '1.6.1', '>=')
                || isset($product['productmega'])) {
                if ((int)$action->reduction_percent_tax && (bool)!$action->apply_discount_to_regular_price) {
                    $productPrice = $product['price_with_reduction'];
                } elseif (!(int)$action->reduction_percent_tax && (bool)!$action->apply_discount_to_regular_price) {
                    $productPrice = $product['price_with_reduction_without_tax'];
                } elseif ((int)$action->reduction_percent_tax && (bool)$action->apply_discount_to_regular_price) {
                    $productPrice = $product['price_without_reduction'];
                } elseif (!(int)$action->reduction_percent_tax && (bool)$action->apply_discount_to_regular_price) {
                    $productPrice = $product['price_without_reduction_without_tax'];
                }

                $unitDiscount = $productPrice*($action->reduction_percent/100);

                if ((bool)$action->apply_discount_to_regular_price) {
                    if ((int)$action->reduction_percent_tax) {
                        $unitDiscount = max(array(0, $product['price_with_reduction'] - ($product['price_without_reduction'] - $unitDiscount)));
                    } else {
                        $unitDiscount = max(array(0, $product['price_with_reduction_without_tax'] - (Product::getPriceStatic($product['id_product'], (int)$action->reduction_percent_tax, (isset($product['id_product_attribute']) ? (int)$product['id_product_attribute'] : null), 6, null, false, false, $product['cart_quantity'], false, (int)$this->context->cart->id_customer ? (int)$this->context->cart->id_customer : null, (int)$this->context->cart->id, $product['id_address'], $specific_price_output, false, true, $virtual_context) - $unitDiscount)));
                    }
                }
            } else {
                $productPrice = Product::getPriceStatic($product['id_product'], (int)$action->reduction_percent_tax, (isset($product['id_product_attribute']) ? (int)$product['id_product_attribute'] : null), 6, null, false, (bool)!$action->apply_discount_to_regular_price, $product['cart_quantity'], false, (int)$this->context->cart->id_customer ? (int)$this->context->cart->id_customer : null, (int)$this->context->cart->id, $product['id_address'], $specific_price_output, false, true, $virtual_context);

                $unitDiscount = $productPrice*($action->reduction_percent/100);

                if ((bool)$action->apply_discount_to_regular_price) {
                    $unitDiscount = max(array(0, Product::getPriceStatic($product['id_product'], (int)$action->reduction_percent_tax, (isset($product['id_product_attribute']) ? (int)$product['id_product_attribute'] : null), 6, null, false, true, $product['cart_quantity'], false, (int)$this->context->cart->id_customer ? (int)$this->context->cart->id_customer : null, (int)$this->context->cart->id, $product['id_address'], $specific_price_output, false, true, $virtual_context) - (Product::getPriceStatic($product['id_product'], (int)$action->reduction_percent_tax, (isset($product['id_product_attribute']) ? (int)$product['id_product_attribute'] : null), 6, null, false, false, $product['cart_quantity'], false, (int)$this->context->cart->id_customer ? (int)$this->context->cart->id_customer : null, (int)$this->context->cart->id, $product['id_address'], $specific_price_output, false, true, $virtual_context) - $unitDiscount)));
                }
            }
        } else {
            if (version_compare(_PS_VERSION_, '1.6.1', '>=')
                || isset($product['productmega'])) {
                if ((int)$action->reduction_tax) {
                    $unitDiscount = $product['price_with_reduction'];
                } else {
                    $unitDiscount = $product['price_with_reduction_without_tax'];
                }
            } else {
                $unitDiscount = Product::getPriceStatic($product['id_product'], (int)$action->reduction_tax, (isset($product['id_product_attribute']) ? (int)$product['id_product_attribute'] : null), 6, null, false, true, $product['cart_quantity'], false, (int)$this->context->cart->id_customer ? (int)$this->context->cart->id_customer : null, (int)$this->context->cart->id, $product['id_address'], $specific_price_output, false, true, $this->context);
            }
        }

        //    Cache::store($cache_key, $unitDiscount);

        return $unitDiscount;
        //} else {
        //    return Cache::retrieve($cache_key);
        //}
    }

    public function compatibleCartRules()
    {
        if (!$this->compatible_cart_rules) {
            $cartRules = $this->context->cart->getCartRules();
            $quantityDiscountRulesAtCart = self::getQuantityDiscountRulesAtCart((int)$this->context->cart->id);

            if (count($cartRules) > count($quantityDiscountRulesAtCart)) {
                return false;
                //return Tools::displayError('This voucher is not combinable with an other voucher already in your cart');
            }
        }

        return true;
    }

    protected function compareValue($operator, $a, $b)
    {
        switch ((int)$operator) {
            case 0:
                if ($a < $b) {
                    return false;
                }
                break;
            case 1:
                if ($a != $b) {
                    return false;
                }
                break;
            case 2:
                if ($a > $b) {
                    return false;
                }
                break;
        }

        return true;
    }

    protected function filterProducts($cartProducts, $object, $use_cache = true)
    {
        if (!is_array($cartProducts) || !is_object($object)) {
            return;
        }

        $cache_key = 'QuantityDiscountRule::filterProducts_'.get_class($object).'_'.(int)$object->getId();
        if (!Cache::isStored($cache_key) || !$use_cache) {
            $cartRules = $this->context->cart->getCartRules(CartRule::FILTER_ACTION_GIFT);

            if ($object->filter_by_product) {
                $restrictionProducts = $object->getSelectedAssociatedRestrictions('product');
            }

            if ($object->filter_by_category) {
                $restrictionCategories = $object->getSelectedAssociatedRestrictions('category');
            }

            if ($object->filter_by_attribute) {
                $restrictionAttributes = $object->getSelectedAssociatedRestrictions('attribute');
            }

            if ($object->filter_by_feature) {
                $restrictionFeatures = $object->getSelectedAssociatedRestrictions('feature');
            }

            if ($object->filter_by_supplier) {
                $restrictionSuppliers = $object->getSelectedAssociatedRestrictions('supplier');
            }

            if ($object->filter_by_manufacturer) {
                $restrictionManufacturers = $object->getSelectedAssociatedRestrictions('manufacturer');
            }

            foreach ($cartProducts as $key => $cartProduct) {
                /* Remove gift products */
                foreach ($cartRules as $cartRule) {
                    if ($cartRule['gift_product']) {
                        if (empty($cartProduct['gift']) && $cartProduct['id_product'] == $cartRule['gift_product'] && $cartProduct['id_product_attribute'] == $cartRule['gift_product_attribute']) {
                            if ($cartProduct['cart_quantity'] > 1) {
                                $cartProducts[$key]['cart_quantity']--;
                            } else {
                                unset($cartProducts[$key]);
                            }
                        }
                    }
                }
            }

            $productsBeforeFilter = count($cartProducts);

            foreach ($cartProducts as $key => $cartProduct) {
                /** Check product */
                if ($object->filter_by_product && (!isset($restrictionProducts['selected']) || !in_array((int)$cartProduct['id_product'], array_column($restrictionProducts['selected'], 'id_product')))) {
                    unset($cartProducts[$key]);
                    continue;
                }

                /** Check categories */
                if ($object->filter_by_category) {
                    if ($object->products_default_category) {
                        if (!isset($restrictionCategories['selected']) || !in_array((int)$cartProduct['id_category_default'], array_column($restrictionCategories['selected'], 'id_category'))) {
                            unset($cartProducts[$key]);
                            continue;
                        }
                    } else {
                        $productIsInCategory = false;
                        $productCategories = Product::getProductCategories($cartProduct['id_product']);
                        foreach ($productCategories as $productCategory) {
                            if (isset($restrictionCategories['selected']) && in_array((int)$productCategory, array_column($restrictionCategories['selected'], 'id_category'))) {
                                $productIsInCategory = true;
                                break;
                            }
                        }

                        if (!$productIsInCategory) {
                            unset($cartProducts[$key]);
                            continue;
                        }
                    }
                }

                /** Check attributes */
                if ($object->filter_by_attribute) {
                    $product = new Product((int)$cartProduct['id_product']);

                    $productHasCombination = false;
                    if (isset($cartProduct['id_product_attribute'])) {
                        if ($combinations = $product->getAttributeCombinationsById((int)$cartProduct['id_product_attribute'], (int)$this->context->cart->id_lang)) {
                            foreach ($combinations as $combination) {
                                //CAUTION! Inverse logic. If product has any of the attributes selected, is considered valid
                                if ((int)$combination['id_attribute'] && in_array((int)$combination['id_attribute'], array_column($restrictionAttributes['selected'], 'id_attribute'))) {
                                    $productHasCombination = true;
                                    break;
                                }
                            }
                        } elseif (in_array(999999, array_column($restrictionAttributes['selected'], 'id_attribute'))) {
                            $productHasCombination = true;
                        }
                    }

                    if (!$productHasCombination) {
                        unset($cartProducts[$key]);
                        continue;
                    }
                }

                /** Check features */
                if ($object->filter_by_feature) {
                    $productFeatures = Product::getFeaturesStatic((int)$cartProduct['id_product']);
                    $productHasFeature = false;
                    if (isset($productFeatures)) {
                        foreach ($productFeatures as $productFeature) {
                            //CAUTION! Inverse logic. If product has any of the features selected, is considered valid
                            if ((int)$productFeature['id_feature_value'] && in_array((int)$productFeature['id_feature_value'], array_column($restrictionFeatures['selected'], 'id_feature'))) {
                                $productHasFeature = true;
                                break;
                            }
                        }
                    } elseif (in_array(999999, array_column($restrictionFeatures['selected'], 'id_feature'))) {
                        $productHasFeature = true;
                    }

                    if (!$productHasFeature) {
                        unset($cartProducts[$key]);
                        continue;
                    }
                }

                /** Check supplier */
                if ($object->filter_by_supplier) {
                    if ((!(int)$cartProduct['id_supplier']  && !in_array(999999, array_column($restrictionSuppliers['selected'], 'id_supplier')))
                        || ((int)$cartProduct['id_supplier'] && !in_array((int)$cartProduct['id_supplier'], array_column($restrictionSuppliers['selected'], 'id_supplier')))) {
                        unset($cartProducts[$key]);
                        continue;
                    }
                }

                /** Check manufacturer */
                if ($object->filter_by_manufacturer) {
                    if ((!(int)$cartProduct['id_manufacturer'] && !in_array(999999, array_column($restrictionManufacturers['selected'], 'id_manufacturer')))
                        || ((int)$cartProduct['id_manufacturer'] && !in_array((int)$cartProduct['id_manufacturer'], array_column($restrictionManufacturers['selected'], 'id_manufacturer')))) {
                        unset($cartProducts[$key]);
                        continue;
                    }
                }

                /** Filter by stock */
                if ($object->filter_by_stock) {
                    $stock = (get_class($object) == 'QuantityDiscountRuleAction' ? $object->stock : $object->product_stock_amount);
                    if (!$this->compareValue((int)$object->stock_operator, StockAvailable::getQuantityAvailableByProduct((int)$cartProduct['id_product'], (int)$cartProduct['id_product_attribute']), $stock)) {
                        unset($cartProducts[$key]);
                        continue;
                    }
                }

                /** Discard products with special price if configured */
                if (!(int)$object->apply_discount_to_special && Product::getPriceStatic($cartProduct['id_product'], false, (isset($cartProduct['id_product_attribute']) ? (int)$cartProduct['id_product_attribute'] : null), 6, null, true, true, $cartProduct['cart_quantity']) > 0) {
                    unset($cartProducts[$key]);
                    continue;
                }

                if (isset($cartProduct['productmega'])) {
                    foreach ($cartProduct['productmega'] as $productmega) {
                        $cartProduct['id_product_attribute'] = (isset($cartProduct['id_product_attribute']) ? (int)$cartProduct['id_product_attribute'] : null);
                        $cartProduct['cart_quantity'] = (int)$productmega['quantity'];
                        $cartProduct['price_without_reduction'] = $productmega['pricewt'];
                        $cartProduct['price_with_reduction'] = $productmega['pricewt'];
                        $cartProduct['price_with_reduction_without_tax'] = $productmega['price'];
                        $cartProduct['price_without_reduction_without_tax'] = $productmega['price'];
                        $cartProduct['price_without_reduction_without_tax'] = $productmega['quantity_available'];
                    }
                } else {
                    if (version_compare(_PS_VERSION_, '1.6.1', '>=')) {
                        if (Configuration::get('PS_TAX_ADDRESS_TYPE') == 'id_address_invoice') {
                            $address_id = (int)$this->context->cart->id_address_invoice;
                        } else {
                            $address_id = (int)$this->context->cart->id_address_delivery;
                        }
                        if (!Address::addressExists($address_id)) {
                            $address_id = null;
                        }

                        $address = Address::initialize($address_id, true);

                        $tax_manager = TaxManagerFactory::getManager($address, Product::getIdTaxRulesGroupByIdProduct((int)$cartProduct['id_product'], $this->context));
                        $product_tax_calculator = $tax_manager->getTaxCalculator();
                        if (isset($cartProduct['price_without_reduction'])) {
                            $cartProduct['price_without_reduction_without_tax'] = Tools::ps_round($product_tax_calculator->removeTaxes($cartProduct['price_without_reduction']), 6);
                        } else {
                            $cartProduct['price_without_reduction_without_tax'] = Tools::ps_round($product_tax_calculator->removeTaxes($cartProduct['unit_price_tax_incl']), 6);
                        }
                    } else {
                        $cartProduct['price_with_reduction'] = $cartProduct['price'];
                        $cartProduct['price_with_reduction_without_tax'] = $cartProduct['price_wt'];
                    }
                }

                /** Filter by price */
                if ($object->filter_by_price) {
                    if (isset($cartProduct['productmega'])) {
                        foreach ($cartProduct['productmega'] as $key2 => $productmega) {
                            if ((int)$object->product_price_from_tax) {
                                $price = $productmega['price_wt'];
                            } else {
                                $price = $productmega['price'];
                            }

                            $price = self::convertPriceFull($price, $this->context->currency, new Currency($object->product_price_from_currency), true);
                            if (!$this->compareValue(0, $price, $object->product_price_from)
                                || !$this->compareValue(2, $price, $object->product_price_to)) {
                                unset($cartProduct[$key2]);
                                continue;
                            }
                        }
                    } else {
                        if (version_compare(_PS_VERSION_, '1.6.1', '>=')) {
                            if ((int)$object->product_price_from_tax) {
                                $price = $cartProduct['price_with_reduction'];
                            } else {
                                $price = $cartProduct['price_with_reduction_without_tax'];
                            }
                        } else {
                            if ((int)$object->product_price_from_tax) {
                                $price = $cartProduct['price_wt'];
                            } else {
                                $price = $cartProduct['price'];
                            }
                        }

                        $price = self::convertPriceFull($price, $this->context->currency, new Currency($object->product_price_from_currency), true);
                        if (!$this->compareValue(0, $price, $object->product_price_from)
                            || !$this->compareValue(2, $price, $object->product_price_to)) {
                            unset($cartProducts[$key]);
                            continue;
                        }
                    }
                }
            }

            if (isset($object->products_all_met) && $object->products_all_met && $productsBeforeFilter != count($cartProducts)) {
                $result = array();
            }


            if (count($cartProducts) && !$this->apply_products_already_discounted && get_class($object) == 'QuantityDiscountRuleAction') {
                // Get an array with id_product-id_product_attribute from $cartProducts
                $cartProductsLight = array();
                foreach ($cartProducts as $key => $cartProduct) {
                    $cartProductsLight[$key] = $cartProduct['id_product'].'-'.$cartProduct['id_product_attribute'];
                }

                foreach (self::$_discountedProducts as $idProductAndAttribute => $discountedProduct) {
                    $key2 = array_search($idProductAndAttribute, $cartProductsLight);

                    if ($key2 !== false) {
                        if ((int)$cartProducts[$key2]['cart_quantity'] > (int)$discountedProduct['quantity']) {
                            $cartProducts[$key2]['cart_quantity'] -= (int)$discountedProduct['quantity'];
                        } else {
                            unset($cartProducts[$key2]);
                        }
                    }
                }
            }

            $result = $cartProducts;

            Cache::store($cache_key, $result);
        } else {
            $result = Cache::retrieve($cache_key);
        }

        return $result;
    }

    protected function productIsInFilter($id_product, $object, $gift_product = false)
    {
        if (!((int)$id_product)) {
            return false;
        }

        $cache_key = 'QuantityDiscountRule::productIsInFilter_'.(int)$id_product.'_'.get_class($object).'_'.(int)$object->getId().'_'.(int)$gift_product;

        if (!Cache::isStored($cache_key)) {
            $product = new Product((int)$id_product);

            /** Check product */
            if ($object->filter_by_product) {
                $restrictionProducts = $object->getSelectedAssociatedRestrictions('product');
                if (!isset($restrictionProducts['selected']) || !in_array((int)$id_product, array_column($restrictionProducts['selected'], 'id_product'))) {
                    Cache::store($cache_key, false);
                    return false;
                }
            }

            /** Check attributes */
            if ($object->filter_by_attribute) {
                $restrictionAttributes = $object->getSelectedAssociatedRestrictions('attribute');
                $product = new Product((int)$id_product);
                $combinations = $product->getAttributeCombinations((int)$this->context->cart->id_lang);

                $productHasCombination = false;
                if ($combinations) {
                    foreach ($combinations as $combination) {
                        //CAUTION! Inverse logic. If product has any of the attributes selected, is considered valid
                        if ((int)$combination['id_attribute'] && in_array((int)$combination['id_attribute'], array_column($restrictionAttributes['selected'], 'id_attribute'))) {
                            $productHasCombination = true;
                            break;
                        }
                    }
                } elseif (in_array(999999, array_column($restrictionAttributes['selected'], 'id_attribute'))) {
                    $productHasCombination = true;
                }

                if (!$productHasCombination) {
                    Cache::store($cache_key, false);
                    return false;
                }
            }

            /** Check features */
            if ($object->filter_by_feature) {
                $restrictionFeatures = $object->getSelectedAssociatedRestrictions('feature');
                $productFeatures = Product::getFeaturesStatic((int)$id_product);

                $productHasFeature = false;

                if (isset($productFeatures)) {
                    foreach ($productFeatures as $productFeature) {
                        //CAUTION! Inverse logic. If product has any of the features selected, is considered valid
                        if ((int)$productFeature['id_feature_value'] && in_array((int)$productFeature['id_feature_value'], array_column($restrictionFeatures['selected'], 'id_feature'))) {
                            $productHasFeature = true;
                            break;
                        }
                    }
                } elseif (in_array(999999, array_column($restrictionFeatures['selected'], 'id_feature'))) {
                    $productHasFeature = true;
                }

                if (!$productHasFeature) {
                    Cache::store($cache_key, false);
                    return false;
                }
            }

            /** Check categories */
            if ($object->filter_by_category) {
                $restrictionCategories = $object->getSelectedAssociatedRestrictions('category');
                if ($object->products_default_category) {
                    if (!isset($restrictionCategories['selected']) || !in_array((int)$product->id_category_default, array_column($restrictionCategories['selected'], 'id_category'))) {
                        Cache::store($cache_key, false);
                        return false;
                    }
                } else {
                    $productIsInCategory = false;
                    $productCategories = Product::getProductCategories((int)$id_product);
                    foreach ($productCategories as $productCategory) {
                        if (isset($restrictionCategories['selected']) && in_array((int)$productCategory, array_column($restrictionCategories['selected'], 'id_category'))) {
                            $productIsInCategory = true;
                            break;
                        }
                    }

                    if (!$productIsInCategory) {
                        Cache::store($cache_key, false);
                        return false;
                    }
                }
            }

            /** Check supplier */
            if ($object->filter_by_supplier) {
                $restrictionSuppliers = $object->getSelectedAssociatedRestrictions('supplier');
                if ((!(int)$product->id_supplier  && !in_array(999999, array_column($restrictionSuppliers['selected'], 'id_supplier')))
                    || ((int)$product->id_supplier && !in_array((int)$product->id_supplier, array_column($restrictionSuppliers['selected'], 'id_supplier')))) {
                    Cache::store($cache_key, false);
                    return false;
                }
            }

            /** Check manufacturer */
            if ($object->filter_by_manufacturer) {
                $restrictionManufacturers = $object->getSelectedAssociatedRestrictions('manufacturer');

                if ((!(int)$product->id_manufacturer  && !in_array(999999, array_column($restrictionManufacturers['selected'], 'id_manufacturer')))
                    || ((int)$product->id_manufacturer && !in_array((int)$product->id_manufacturer, array_column($restrictionManufacturers['selected'], 'id_manufacturer')))) {
                    Cache::store($cache_key, false);
                    return false;
                }
            }

            /** Filter by price */
            if ($object->filter_by_price) {
                $price = Product::getPriceStatic((int)$id_product, (int)$object->product_price_from_tax);
                $price = self::convertPriceFull($price, $this->context->currency, new Currency($object->product_price_from_currency), true);
                if (!$this->compareValue(0, $price, $object->product_price_from)
                    || !$this->compareValue(2, $price, $object->product_price_to)) {
                    Cache::store($cache_key, false);
                    return false;
                }
            }

            /** Filter by stock */
            if ($object->filter_by_stock) {
                $stock = (get_class($object) == 'QuantityDiscountRuleAction' ? $object->stock : $object->product_stock_amount);
                if (!$this->compareValue((int)$object->stock_operator, StockAvailable::getQuantityAvailableByProduct((int)$product->id), $stock)) {
                    Cache::store($cache_key, false);
                    return false;
                }
            }

            /** Discard products with special price if configured */
            if (!(int)$object->apply_discount_to_special && Product::getPriceStatic((int)$id_product, false, null, 6, null, true) > 0) {
                Cache::store($cache_key, false);
                return false;
            }

            /* Gift product */
            if ($gift_product && isset($object->gift_product) && (int)$object->gift_product) {
                if ($id_product != $object->gift_product) {
                    Cache::store($cache_key, false);
                    return false;
                }
            }

            Cache::store($cache_key, true);
            return true;
        } else {
            return Cache::retrieve($cache_key);
        }
    }

    protected function groupProducts($id_cart, $products, $object)
    {
        if (!is_array($products)) {
            return false;
        }

        if (!$key = $object->group_products_by) {
            return false;
        }

        $cache_key = 'QuantityDiscountRule::groupProducts_'.(int)$id_cart.'_'.get_class($object).'_'.(int)$object->getId().'_'.$key;

        if (!Cache::isStored($cache_key)) {
            switch ($key) {
                case 'product':
                    if ((int)$object->products_nb_same_attributes) {
                        $key = 'by_product';
                    } else {
                        $key = 'by_product_attribute';
                    }
                    break;

                case 'category':
                    if (!$object->filter_by_category || $object->products_default_category) {
                        $key = 'by_default_category';
                    } elseif ($object->filter_by_category) {
                        if ($object->products_default_category) {
                            $key = 'by_default_category';
                        } else {
                            $key = 'by_category';
                        }
                    }
                    break;

                case 'supplier':
                    $key = 'by_supplier';
                    break;

                case 'manufacturer':
                    $key = 'by_manufacturer';
                    break;

                case 'all':
                    $key = 'by_all';
                    break;
            }

            $productsGrouped = array();

            if (Configuration::get('PS_TAX_ADDRESS_TYPE') == 'id_address_invoice') {
                $address_id = (int)$this->context->cart->id_address_invoice;
            } else {
                $address_id = (int)$this->context->cart->id_address_delivery;
            }
            if (!Address::addressExists($address_id)) {
                $address_id = null;
            }

            $address = Address::initialize($address_id, true);

            foreach ($products as $product) {
                if (version_compare(_PS_VERSION_, '1.6.1', '<')) {
                    $quantity = (int)$product['cart_quantity'];
                    $product = Product::getProductProperties($this->context->language->id, $product);
                    $product['cart_quantity'] = $quantity;
                }

                if ($key == 'by_product'
                    || $key == 'by_attribute'
                    || $key == 'by_product_attribute'
                    || $key == 'by_default_category'
                    || $key == 'by_supplier'
                    || $key == 'by_manufacturer') {
                    switch ($key) {
                        case 'by_product':
                            $index = $product['id_product'];
                            break;
                        case 'by_attribute':
                            $index = $product['id_product_attribute'];
                            break;
                        case 'by_product_attribute':
                            $index = $product['id_product'].'-'.$product['id_product_attribute'];
                            break;
                        case 'by_default_category':
                            $index = $product['id_category_default'];
                            break;
                        case 'by_supplier':
                            $index = $product['id_supplier'];
                            break;
                        case 'by_manufacturer':
                            $index = $product['id_manufacturer'];
                            break;
                    }

                    if (isset($product['productmega'])) {
                        foreach ($product['productmega'] as $productmega) {
                            $index2 = $product['id_product'].'-'.$product['id_product_attribute'].'-'.$productmega['id_megacart'];

                            $productsGrouped[$index]['products'][$index2]['id_product'] = (int)$product['id_product'];
                            $productsGrouped[$index]['products'][$index2]['id_product_attribute'] = (isset($product['id_product_attribute']) ? (int)$product['id_product_attribute'] : null);
                            $productsGrouped[$index]['products'][$index2]['id_category_default'] = (int)$product['id_category_default'];

                            $productsGrouped[$index]['products'][$index2]['cart_quantity'] = (int)$productmega['quantity'];
                            $productsGrouped[$index]['products'][$index2]['price_without_reduction'] = $productmega['pricewt'];
                            $productsGrouped[$index]['products'][$index2]['price_with_reduction'] = $productmega['pricewt'];
                            $productsGrouped[$index]['products'][$index2]['price_with_reduction_without_tax'] = $productmega['price'];
                            $productsGrouped[$index]['products'][$index2]['price_without_reduction_without_tax'] = $productmega['price'];
                            $productsGrouped[$index]['products'][$index2]['quantity_available'] = $productmega['quantity_available'];

                            if (isset($productsGrouped[$index]['price_without_reduction'])) {
                                $productsGrouped[$index]['price_without_reduction'] += $productmega['pricewt']*(int)$productmega['quantity'];
                            } else {
                                $productsGrouped[$index]['price_without_reduction'] = $productmega['pricewt']*(int)$productmega['quantity'];
                            }

                            if (isset($productsGrouped[$index]['price_with_reduction'])) {
                                $productsGrouped[$index]['price_with_reduction'] += $productmega['pricewt']*(int)$productmega['quantity'];
                            } else {
                                $productsGrouped[$index]['price_with_reduction'] = $productmega['pricewt']*(int)$productmega['quantity'];
                            }

                            if (isset($productsGrouped[$index]['price_with_reduction_without_tax'])) {
                                $productsGrouped[$index]['price_with_reduction_without_tax'] += $productmega['price']*(int)$productmega['quantity'];
                            } else {
                                $productsGrouped[$index]['price_with_reduction_without_tax'] = $productmega['price']*(int)$productmega['quantity'];
                            }

                            if (isset($productsGrouped[$index]['price_without_reduction_without_tax'])) {
                                $productsGrouped[$index]['price_without_reduction_without_tax'] += $productmega['price']*(int)$productmega['quantity'];
                            } else {
                                $productsGrouped[$index]['price_without_reduction_without_tax'] = $productmega['price']*(int)$productmega['quantity'];
                            }
                        }
                    } else {
                        $index2 = $product['id_product'].'-'.$product['id_product_attribute'];

                        $productsGrouped[$index]['products'][$index2]['id_product'] = (int)$product['id_product'];
                        $productsGrouped[$index]['products'][$index2]['id_product_attribute'] = (isset($product['id_product_attribute']) ? (int)$product['id_product_attribute'] : null);
                        $productsGrouped[$index]['products'][$index2]['id_category_default'] = (int)$product['id_category_default'];

                        $productsGrouped[$index]['products'][$index2]['cart_quantity'] = (int)$product['cart_quantity'];

                        $productsGrouped[$index]['products'][$index2]['quantity_available'] = ((isset($object->apply_discount_to_stock) && $object->apply_discount_to_stock && Configuration::get('PS_STOCK_MANAGEMENT')) ? (int)$product['quantity_available'] : PHP_INT_MAX);

                        if (version_compare(_PS_VERSION_, '1.6.1', '>=')) {
                            $productsGrouped[$index]['products'][$index2]['price_without_reduction'] = $product['price_without_reduction'];
                            $productsGrouped[$index]['products'][$index2]['price_with_reduction'] = $product['price_with_reduction'];
                            $productsGrouped[$index]['products'][$index2]['price_with_reduction_without_tax'] = $product['price_with_reduction_without_tax'];

                            $tax_manager = TaxManagerFactory::getManager($address, Product::getIdTaxRulesGroupByIdProduct((int)$product['id_product'], $this->context));
                            $product_tax_calculator = $tax_manager->getTaxCalculator();
                            $price_without_reduction_without_tax = Tools::ps_round($product_tax_calculator->removeTaxes($product['price_without_reduction']), 6);
                            $productsGrouped[$index]['products'][$index2]['price_without_reduction_without_tax'] = $price_without_reduction_without_tax;
                        } else {
                            $productsGrouped[$index]['products'][$index2]['price_with_reduction'] = $product['price'];
                            $productsGrouped[$index]['products'][$index2]['price_with_reduction_without_tax'] = $product['price_wt'];
                            $price_without_reduction_without_tax = $product['price_wt'];
                        }

                        if (isset($productsGrouped[$index]['price_without_reduction'])) {
                            $productsGrouped[$index]['price_without_reduction'] += $product['price_without_reduction']*(int)$product['cart_quantity'];
                        } else {
                            $productsGrouped[$index]['price_without_reduction'] = $product['price_without_reduction']*(int)$product['cart_quantity'];
                        }

                        if (isset($productsGrouped[$index]['price_with_reduction'])) {
                            if (version_compare(_PS_VERSION_, '1.6.1', '>=')) {
                                $productsGrouped[$index]['price_with_reduction'] += $product['price_with_reduction']*(int)$product['cart_quantity'];
                            } else {
                                $productsGrouped[$index]['price_with_reduction'] += $product['price']*(int)$product['cart_quantity'];
                            }
                        } else {
                            if (version_compare(_PS_VERSION_, '1.6.1', '>=')) {
                                $productsGrouped[$index]['price_with_reduction'] = $product['price_with_reduction']*(int)$product['cart_quantity'];
                            } else {
                                $productsGrouped[$index]['price_with_reduction'] = $product['price']*(int)$product['cart_quantity'];
                            }
                        }

                        if (isset($productsGrouped[$index]['price_with_reduction_without_tax'])) {
                            if (version_compare(_PS_VERSION_, '1.6.1', '>=')) {
                                $productsGrouped[$index]['price_with_reduction_without_tax'] += $product['price_with_reduction_without_tax']*(int)$product['cart_quantity'];
                            } else {
                                $productsGrouped[$index]['price_with_reduction_without_tax'] += $product['price_wt']*(int)$product['cart_quantity'];
                            }
                        } else {
                            if (version_compare(_PS_VERSION_, '1.6.1', '>=')) {
                                $productsGrouped[$index]['price_with_reduction_without_tax'] = $product['price_with_reduction_without_tax']*(int)$product['cart_quantity'];
                            } else {
                                $productsGrouped[$index]['price_with_reduction_without_tax'] = $product['price_wt']*(int)$product['cart_quantity'];
                            }
                        }

                        if (isset($productsGrouped[$index]['price_without_reduction_without_tax'])) {
                            $productsGrouped[$index]['price_without_reduction_without_tax'] += $price_without_reduction_without_tax*(int)$product['cart_quantity'];
                        } else {
                            $productsGrouped[$index]['price_without_reduction_without_tax'] = $price_without_reduction_without_tax*(int)$product['cart_quantity'];
                        }
                    }

                    $productsGrouped[$index]['products'][$index2]['id_shop'] = (int)$product['id_shop'];
                    if (Configuration::get('PS_TAX_ADDRESS_TYPE') == 'id_address_invoice') {
                        $productsGrouped[$index]['products'][$index2]['id_address'] = (int)$this->context->cart->id_address_invoice;
                    } else {
                        $productsGrouped[$index]['products'][$index2]['id_address'] = (int)$product['id_address_delivery']; // Get delivery address of the product from the cart
                    }

                    if (isset($productsGrouped[$index]['cart_quantity'])) {
                        $productsGrouped[$index]['cart_quantity'] += (int)$product['cart_quantity'];
                    } else {
                        $productsGrouped[$index]['cart_quantity'] = (int)$product['cart_quantity'];
                    }
                } elseif ($key == 'by_category') {
                    $productCategories = Product::getProductCategories($product['id_product']);
                    $productIsInCategory = false;
                    $categories = $object->getSelectedAssociatedRestrictions('category');

                    foreach ($productCategories as $productCategory) {
                        if (in_array((int)$productCategory, array_column($categories['selected'], 'id_category'))) {
                            $productIsInCategory[] = $productCategory;
                            continue;
                        }
                    }

                    if ($productIsInCategory) {
                        foreach ($productIsInCategory as $productCategory) {
                            if (isset($product['productmega'])) {
                                foreach ($product['productmega'] as $productmega) {
                                    $index2 = $product['id_product'].'-'.$product['id_product_attribute'].'-'.$productmega['id_megacart'];

                                    $productsGrouped[$productCategory]['products'][$index2]['id_product'] = (int)$product['id_product'];
                                    $productsGrouped[$productCategory]['products'][$index2]['id_product_attribute'] = (isset($product['id_product_attribute']) ? (int)$product['id_product_attribute'] : null);
                                    $productsGrouped[$productCategory]['products'][$index2]['id_category_default'] = (int)$product['id_category_default'];

                                    $productsGrouped[$productCategory]['products'][$index2]['cart_quantity'] = (int)$productmega['quantity'];
                                    $productsGrouped[$productCategory]['products'][$index2]['price_without_reduction'] = $productmega['pricewt'];
                                    $productsGrouped[$productCategory]['products'][$index2]['price_with_reduction'] = $productmega['pricewt'];
                                    $productsGrouped[$productCategory]['products'][$index2]['price_with_reduction_without_tax'] = $productmega['price'];
                                    $productsGrouped[$productCategory]['products'][$index2]['price_without_reduction_without_tax'] = $productmega['price'];
                                    $productsGrouped[$productCategory]['products'][$index2]['quantity_available'] = $productmega['quantity_available'];

                                    if (isset($productsGrouped[$productCategory]['price_without_reduction'])) {
                                        $productsGrouped[$productCategory]['price_without_reduction'] += $productmega['pricewt']*(int)$productmega['quantity'];
                                    } else {
                                        $productsGrouped[$productCategory]['price_without_reduction'] = $productmega['pricewt']*(int)$productmega['quantity'];
                                    }

                                    if (isset($productsGrouped[$productCategory]['price_with_reduction'])) {
                                        $productsGrouped[$productCategory]['price_with_reduction'] += $productmega['pricewt']*(int)$productmega['quantity'];
                                    } else {
                                        $productsGrouped[$productCategory]['price_with_reduction'] = $productmega['pricewt']*(int)$productmega['quantity'];
                                    }

                                    if (isset($productsGrouped[$productCategory]['price_with_reduction_without_tax'])) {
                                        $productsGrouped[$productCategory]['price_with_reduction_without_tax'] += $productmega['price']*(int)$productmega['quantity'];
                                    } else {
                                        $productsGrouped[$productCategory]['price_with_reduction_without_tax'] = $productmega['price']*(int)$productmega['quantity'];
                                    }

                                    if (isset($productsGrouped[$productCategory]['price_without_reduction_without_tax'])) {
                                        $productsGrouped[$productCategory]['price_without_reduction_without_tax'] += $productmega['price']*(int)$productmega['quantity'];
                                    } else {
                                        $productsGrouped[$productCategory]['price_without_reduction_without_tax'] = $productmega['price']*(int)$productmega['quantity'];
                                    }
                                }
                            } else {
                                $index2 = $product['id_product'].'-'.$product['id_product_attribute'];

                                $productsGrouped[$productCategory]['products'][$index2]['id_product'] = (int)$product['id_product'];
                                $productsGrouped[$productCategory]['products'][$index2]['cart_quantity'] = (int)$product['cart_quantity'];
                                $productsGrouped[$productCategory]['products'][$index2]['id_product_attribute'] = (isset($product['id_product_attribute']) ? (int)$product['id_product_attribute'] : null);
                                $productsGrouped[$productCategory]['products'][$index2]['id_category_default'] = (int)$product['id_category_default'];
                                $productsGrouped[$productCategory]['products'][$index2]['quantity_available'] = ((isset($object->apply_discount_to_stock) && $object->apply_discount_to_stock && Configuration::get('PS_STOCK_MANAGEMENT')) ? (int)$product['quantity_available'] : PHP_INT_MAX);

                                if (version_compare(_PS_VERSION_, '1.6.1', '>=')) {
                                    $productsGrouped[$productCategory]['products'][$index2]['price_without_reduction'] = $product['price_without_reduction'];
                                    $productsGrouped[$productCategory]['products'][$index2]['price_with_reduction'] = $product['price_with_reduction'];
                                    $productsGrouped[$productCategory]['products'][$index2]['price_with_reduction_without_tax'] = $product['price_with_reduction_without_tax'];

                                    $tax_manager = TaxManagerFactory::getManager($address, Product::getIdTaxRulesGroupByIdProduct((int)$product['id_product'], $this->context));
                                    $product_tax_calculator = $tax_manager->getTaxCalculator();
                                    $productsGrouped[$productCategory]['products'][$index2]['price_without_reduction_without_tax'] =
                                    $price_without_reduction_without_tax = Tools::ps_round($product_tax_calculator->removeTaxes($product['price_without_reduction']), 6);
                                    $productsGrouped[$productCategory]['products'][$index2]['price_without_reduction_without_tax'] = $price_without_reduction_without_tax;
                                } else {
                                    $productsGrouped[$productCategory]['products'][$index2]['price_with_reduction'] = $product['price'];
                                    $productsGrouped[$productCategory]['products'][$index2]['price_with_reduction_without_tax'] = $product['price_wt'];
                                    $price_without_reduction_without_tax = $product['price_wt'];
                                }

                                if (isset($productsGrouped[$productCategory]['price_without_reduction'])) {
                                    $productsGrouped[$productCategory]['price_without_reduction'] += $product['price_without_reduction']*(int)$product['cart_quantity'];
                                } else {
                                    $productsGrouped[$productCategory]['price_without_reduction'] = $product['price_without_reduction']*(int)$product['cart_quantity'];
                                }

                                if (isset($productsGrouped[$productCategory]['price_with_reduction'])) {
                                    if (version_compare(_PS_VERSION_, '1.6.1', '>=')) {
                                        $productsGrouped[$productCategory]['price_with_reduction'] += $product['price_with_reduction']*(int)$product['cart_quantity'];
                                    } else {
                                        $productsGrouped[$productCategory]['price_with_reduction'] += $product['price']*(int)$product['cart_quantity'];
                                    }
                                } else {
                                    if (version_compare(_PS_VERSION_, '1.6.1', '>=')) {
                                        $productsGrouped[$productCategory]['price_with_reduction'] = $product['price_with_reduction']*(int)$product['cart_quantity'];
                                    } else {
                                        $productsGrouped[$productCategory]['price_with_reduction'] = $product['price']*(int)$product['cart_quantity'];
                                    }
                                }

                                if (isset($productsGrouped[$productCategory]['price_with_reduction_without_tax'])) {
                                    if (version_compare(_PS_VERSION_, '1.6.1', '>=')) {
                                        $productsGrouped[$productCategory]['price_with_reduction_without_tax'] += $product['price_with_reduction_without_tax']*(int)$product['cart_quantity'];
                                    } else {
                                        $productsGrouped[$productCategory]['price_with_reduction_without_tax'] += $product['price_wt']*(int)$product['cart_quantity'];
                                    }
                                } else {
                                    if (version_compare(_PS_VERSION_, '1.6.1', '>=')) {
                                        $productsGrouped[$productCategory]['price_with_reduction_without_tax'] = $product['price_with_reduction_without_tax']*(int)$product['cart_quantity'];
                                    } else {
                                        $productsGrouped[$productCategory]['price_with_reduction_without_tax'] = $product['price_wt']*(int)$product['cart_quantity'];
                                    }
                                }

                                if (isset($productsGrouped[$productCategory]['price_without_reduction_without_tax'])) {
                                    $productsGrouped[$productCategory]['price_without_reduction_without_tax'] += $price_without_reduction_without_tax*(int)$product['cart_quantity'];
                                } else {
                                    $productsGrouped[$productCategory]['price_without_reduction_without_tax'] = $price_without_reduction_without_tax*(int)$product['cart_quantity'];
                                }
                            }

                            $productsGrouped[$productCategory]['products'][$index2]['id_shop'] = (int)$product['id_shop'];
                            if (Configuration::get('PS_TAX_ADDRESS_TYPE') == 'id_address_invoice') {
                                $productsGrouped[$productCategory]['products'][$index2]['id_address'] = (int)$this->context->cart->id_address_invoice;
                            } else {
                                $productsGrouped[$productCategory]['products'][$index2]['id_address'] = (int)$product['id_address_delivery']; // Get delivery address of the product from the cart
                            }

                            if (isset($productsGrouped[$productCategory]['cart_quantity'])) {
                                $productsGrouped[$productCategory]['cart_quantity'] += (int)$product['cart_quantity'];
                            } else {
                                $productsGrouped[$productCategory]['cart_quantity'] = (int)$product['cart_quantity'];
                            }
                        }
                    }
                } elseif ($key == 'by_all') {
                    if (isset($product['productmega'])) {
                        foreach ($product['productmega'] as $productmega) {
                            $index2 = $product['id_product'].'-'.$product['id_product_attribute'].'-'.$productmega['id_megacart'];

                            $productsGrouped[0]['products'][$index2]['id_product'] = (int)$product['id_product'];
                            $productsGrouped[0]['products'][$index2]['id_product_attribute'] = (isset($product['id_product_attribute']) ? (int)$product['id_product_attribute'] : null);
                            $productsGrouped[0]['products'][$index2]['id_category_default'] = (int)$product['id_category_default'];

                            $productsGrouped[0]['products'][$index2]['cart_quantity'] = (int)$productmega['quantity'];
                            $productsGrouped[0]['products'][$index2]['price_without_reduction'] = $productmega['pricewt'];
                            $productsGrouped[0]['products'][$index2]['price_with_reduction'] = $productmega['pricewt'];
                            $productsGrouped[0]['products'][$index2]['price_with_reduction_without_tax'] = $productmega['price'];
                            $productsGrouped[0]['products'][$index2]['price_without_reduction_without_tax'] = $productmega['price'];
                            $productsGrouped[0]['products'][$index2]['quantity_available'] = $productmega['quantity_available'];

                            if (isset($productsGrouped[0]['price_without_reduction'])) {
                                $productsGrouped[0]['price_without_reduction'] += $productmega['pricewt']*(int)$productmega['quantity'];
                            } else {
                                $productsGrouped[0]['price_without_reduction'] = $productmega['pricewt']*(int)$productmega['quantity'];
                            }

                            if (isset($productsGrouped[0]['price_with_reduction'])) {
                                $productsGrouped[0]['price_with_reduction'] += $productmega['pricewt']*(int)$productmega['quantity'];
                            } else {
                                $productsGrouped[0]['price_with_reduction'] = $productmega['pricewt']*(int)$productmega['quantity'];
                            }

                            if (isset($productsGrouped[0]['price_with_reduction_without_tax'])) {
                                $productsGrouped[0]['price_with_reduction_without_tax'] += $productmega['price']*(int)$productmega['quantity'];
                            } else {
                                $productsGrouped[0]['price_with_reduction_without_tax'] = $productmega['price']*(int)$productmega['quantity'];
                            }

                            if (isset($productsGrouped[0]['price_without_reduction_without_tax'])) {
                                $productsGrouped[0]['price_without_reduction_without_tax'] += $productmega['price']*(int)$productmega['quantity'];
                            } else {
                                $productsGrouped[0]['price_without_reduction_without_tax'] = $productmega['price']*(int)$productmega['quantity'];
                            }
                        }
                    } else {
                        $index2 = $product['id_product'].'-'.$product['id_product_attribute'];

                        $productsGrouped[0]['products'][$index2]['id_product'] = (int)$product['id_product'];
                        $productsGrouped[0]['products'][$index2]['cart_quantity'] = (int)$product['cart_quantity'];
                        $productsGrouped[0]['products'][$index2]['id_product_attribute'] = (isset($product['id_product_attribute']) ? (int)$product['id_product_attribute'] : null);
                        $productsGrouped[0]['products'][$index2]['id_category_default'] = (int)$product['id_category_default'];
                        $productsGrouped[0]['products'][$index2]['quantity_available'] = ((isset($object->apply_discount_to_stock) && $object->apply_discount_to_stock && Configuration::get('PS_STOCK_MANAGEMENT')) ? (int)$product['quantity_available'] : PHP_INT_MAX);

                        if (version_compare(_PS_VERSION_, '1.6.1', '>=')) {
                            $productsGrouped[0]['products'][$index2]['price_without_reduction'] = $product['price_without_reduction'];
                            $productsGrouped[0]['products'][$index2]['price_with_reduction'] = $product['price_with_reduction'];
                            $productsGrouped[0]['products'][$index2]['price_with_reduction_without_tax'] = $product['price_with_reduction_without_tax'];

                            $tax_manager = TaxManagerFactory::getManager($address, Product::getIdTaxRulesGroupByIdProduct((int)$product['id_product'], $this->context));
                            $product_tax_calculator = $tax_manager->getTaxCalculator();
                            $price_without_reduction_without_tax = Tools::ps_round($product_tax_calculator->removeTaxes($product['price_without_reduction']), 6);
                            $productsGrouped[0]['products'][$index2]['price_without_reduction_without_tax'] = $price_without_reduction_without_tax;
                        } else {
                            $productsGrouped[0]['products'][$index2]['price_with_reduction'] = $product['price'];
                            $productsGrouped[0]['products'][$index2]['price_with_reduction_without_tax'] = $product['price_wt'];
                            $price_without_reduction_without_tax = $product['price_wt'];
                        }

                        if (isset($productsGrouped[0]['price_without_reduction'])) {
                            $productsGrouped[0]['price_without_reduction'] += $product['price_without_reduction']*(int)$product['cart_quantity'];
                        } else {
                            $productsGrouped[0]['price_without_reduction'] = $product['price_without_reduction']*(int)$product['cart_quantity'];
                        }

                        if (isset($productsGrouped[0]['price_with_reduction'])) {
                            if (version_compare(_PS_VERSION_, '1.6.1', '>=')) {
                                $productsGrouped[0]['price_with_reduction'] += $product['price_with_reduction']*(int)$product['cart_quantity'];
                            } else {
                                $productsGrouped[0]['price_with_reduction'] += $product['price']*(int)$product['cart_quantity'];
                            }
                        } else {
                            if (version_compare(_PS_VERSION_, '1.6.1', '>=')) {
                                $productsGrouped[0]['price_with_reduction'] = $product['price_with_reduction']*(int)$product['cart_quantity'];
                            } else {
                                $productsGrouped[0]['price_with_reduction'] = $product['price']*(int)$product['cart_quantity'];
                            }
                        }

                        if (isset($productsGrouped[0]['price_with_reduction_without_tax'])) {
                            if (version_compare(_PS_VERSION_, '1.6.1', '>=')) {
                                $productsGrouped[0]['price_with_reduction_without_tax'] += $product['price_with_reduction_without_tax']*(int)$product['cart_quantity'];
                            } else {
                                $productsGrouped[0]['price_with_reduction_without_tax'] += $product['price_wt']*(int)$product['cart_quantity'];
                            }
                        } else {
                            if (version_compare(_PS_VERSION_, '1.6.1', '>=')) {
                                $productsGrouped[0]['price_with_reduction_without_tax'] = $product['price_with_reduction_without_tax']*(int)$product['cart_quantity'];
                            } else {
                                $productsGrouped[0]['price_with_reduction_without_tax'] = $product['price_wt']*(int)$product['cart_quantity'];
                            }
                        }

                        if (isset($productsGrouped[0]['price_without_reduction_without_tax'])) {
                            $productsGrouped[0]['price_without_reduction_without_tax'] += $price_without_reduction_without_tax*(int)$product['cart_quantity'];
                        } else {
                            $productsGrouped[0]['price_without_reduction_without_tax'] = $price_without_reduction_without_tax*(int)$product['cart_quantity'];
                        }
                    }

                    $productsGrouped[0]['products'][$index2]['id_shop'] = (int)$product['id_shop'];
                    if (Configuration::get('PS_TAX_ADDRESS_TYPE') == 'id_address_invoice') {
                        $productsGrouped[0]['products'][$index2]['id_address'] = (int)$this->context->cart->id_address_invoice;
                    } else {
                        $productsGrouped[0]['products'][$index2]['id_address'] = (int)$product['id_address_delivery']; // Get delivery address of the product from the cart
                    }

                    if (isset($productsGrouped[0]['cart_quantity'])) {
                        $productsGrouped[0]['cart_quantity'] += (int)$product['cart_quantity'];
                    } else {
                        $productsGrouped[0]['cart_quantity'] = (int)$product['cart_quantity'];
                    }
                }
            }

            $result = $productsGrouped;
            Cache::store($cache_key, $result);
        } else {
            $result = Cache::retrieve($cache_key);
        }

        return $result;
    }

    protected function addDiscountedProducts($action, $products, $quantity)
    {
        foreach ($products as $key => $product) {
            if (!isset(self::$_discountedProducts[$key])) {
                self::$_discountedProducts[$key]['quantity'] = min((int)$product['cart_quantity'], (int)$quantity);
            } else {
                self::$_discountedProducts[$key]['quantity'] += min((int)$product['cart_quantity'], (int)$quantity);
            }

            $quantity -= min((int)$product['cart_quantity'], (int)$quantity);
            if (!$quantity) {
                break;
            }
        }
    }

    public static function getOrdersIdByDateAndState($date_from, $date_to, $id_order_states = null, $id_customer = null, $type = null)
    {
        $sql = 'SELECT `id_order`
                FROM `'._DB_PREFIX_.'orders`
                WHERE DATE_ADD(`date_upd`, INTERVAL -1 DAY) <= \''.pSQL($date_to).'\' AND `date_upd` >= \''.pSQL($date_from).'\'
                    '.Shop::addSqlRestriction()
                    .($type ? ' AND `'.pSQL((string)$type).'_number` != 0' : '')
                    .($id_customer ? ' AND `id_customer` = '.(int)($id_customer) : '')
                    .($id_order_states ? ' AND `current_state` IN ('.implode(',', array_map('intval', $id_order_states)).')' : '');

        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);

        $orders = array();
        foreach ($result as $order) {
            $orders[] = (int)($order['id_order']);
        }

        return $orders;
    }

    public function isAlreadyInCart($id_cart, $id_quantity_discount_rule)
    {
        if (!(int)$id_cart || !(int)$id_quantity_discount_rule) {
            return false;
        }

        $sql = 'SELECT id_cart_rule
            FROM `'._DB_PREFIX_.'quantity_discount_rule_cart`
            WHERE `id_cart` = '.(int)$id_cart.'
                AND `id_quantity_discount_rule` = '.(int)$id_quantity_discount_rule;

        $result = Db::getInstance()->getRow($sql);

        if (isset($result['id_cart_rule'])) {
            return true;
        } else {
            return false;
        }
    }

    public static function isCurrentlyUsed($table = null, $has_active_column = false)
    {
        if ($table === null) {
            $table = self::$definition['table'];
        }

        $query = new DbQuery();
        $query->select('`id_'.bqSQL($table).'`');
        $query->from($table);
        if ($has_active_column) {
            $query->where('`active` = 1');
        }

        return (bool)Db::getInstance()->getValue($query);
    }

    public static function removeUnusedRules($id_quantity_discount_rule = null)
    {
        $sql = 'SELECT `id_cart`, `id_quantity_discount_rule`, `id_cart_rule`
                FROM `'._DB_PREFIX_.'quantity_discount_rule_cart` qdrc
                WHERE qdrc.`id_cart_rule` NOT IN (SELECT `id_cart_rule` FROM `'._DB_PREFIX_.'order_cart_rule`)'.
                ($id_quantity_discount_rule ? ' AND `id_quantity_discount_rule` = '.(int)$id_quantity_discount_rule : '');

        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
        foreach ($result as $rule) {
            $cartRule = new CartRule((int)$rule['id_cart_rule']);
            $cart = new Cart((int)$rule['id_cart']);

            if (!Db::getInstance()->execute("DELETE FROM `"._DB_PREFIX_."quantity_discount_rule_cart`
                WHERE `id_cart` = ".(int)$cart->id." AND `id_cart_rule` =".(int)$rule['id_cart_rule'])
                || !$cart->removeCartRule((int)$rule['id_cart_rule'])
                || !$cartRule->delete()) {
                return false;
            }
        }

        return true;
    }

    public function getGiftProductsValue($with_taxes)
    {
        $products = $this->context->cart->getProducts();
        $cartRules = $this->context->cart->getCartRules(CartRule::FILTER_ACTION_GIFT);

        $amount = 0;

        /** Remove amount of gift products */
        foreach ($cartRules as $cartRule) {
            if ($cartRule['gift_product']) {
                foreach ($products as $product) {
                    if (empty($product['gift']) && $product['id_product'] == $cartRule['gift_product'] && $product['id_product_attribute'] == $cartRule['gift_product_attribute']) {
                        $amount += Tools::ps_round($product[$with_taxes ? 'price_wt' : 'price'], (int)$this->context->currency->decimals * _PS_PRICE_DISPLAY_PRECISION_);
                    }
                }
            }
        }

        return $amount;
    }

        /**
     *
     * Convert amount from a currency to an other currency automatically
     * @param float $amount
     * @param Currency $currency_from if null we used the default currency
     * @param Currency $currency_to if null we used the default currency
     */
    public static function convertPriceFull($amount, Currency $currency_from = null, Currency $currency_to = null, $round = true)
    {
        if ($currency_from === $currency_to) {
            return $amount;
        }

        if ($currency_from === null) {
            $currency_from = new Currency(Configuration::get('PS_CURRENCY_DEFAULT'));
        }

        if ($currency_to === null) {
            $currency_to = new Currency(Configuration::get('PS_CURRENCY_DEFAULT'));
        }

        if ($currency_from->id == Configuration::get('PS_CURRENCY_DEFAULT')) {
            $amount *= $currency_to->conversion_rate;
        } else {
            $conversion_rate = ($currency_from->conversion_rate == 0 ? 1 : $currency_from->conversion_rate);
            // Convert amount to default currency (using the old currency rate)
            $amount = $amount / $conversion_rate;
            // Convert to new currency
            $amount *= $currency_to->conversion_rate;
        }
        if ($round) {
            $amount = Tools::ps_round($amount, _PS_PRICE_DISPLAY_PRECISION_);
        }

        return $amount;
    }

    public static function convertPriceWithConversionRate($amount, $conversion_rate, $round = true)
    {
        $amount = $amount / $conversion_rate;

        if ($round) {
            $amount = Tools::ps_round($amount, _PS_PRICE_DISPLAY_PRECISION_);
        }

        return $amount;
    }

    // https://stackoverflow.com/a/16788610/1136132
    protected function makeComparer()
    {
        // Normalize criteria up front so that the comparer finds everything tidy
        $criteria = func_get_args();
        foreach ($criteria as $index => $criterion) {
            $criteria[$index] = is_array($criterion)
                ? array_pad($criterion, 3, null)
                : array($criterion, SORT_ASC, null);
        }

        $cache_key = 'QuantityDiscountRule::makeComparer'.'_'.$this->context->cart->id.'_'.$criteria[0][0].'_'.$criteria[0][1].'_'.$criteria[0][2];

        if (!Cache::isStored($cache_key)) {
            $result = function ($first, $second) use (&$criteria) {
                foreach ($criteria as $criterion) {
                    // How will we compare this round?
                    list($column, $sortOrder, $projection) = $criterion;
                    $sortOrder = $sortOrder === SORT_DESC ? -1 : 1;

                    // If a projection was defined project the values now
                    if ($projection) {
                        $lhs = call_user_func($projection, $first[$column]);
                        $rhs = call_user_func($projection, $second[$column]);
                    } else {
                        $lhs = $first[$column];
                        $rhs = $second[$column];
                    }

                    // Do the actual comparison; do not return if equal
                    if ($lhs < $rhs) {
                        return -1 * $sortOrder;
                    } else if ($lhs > $rhs) {
                        return 1 * $sortOrder;
                    }
                }

                return 0; // tiebreakers exhausted, so $first == $second
            };

            Cache::store($cache_key, $result);
        } else {
            $result = Cache::retrieve($cache_key);
        }

        return $result;
    }

    // https://stackoverflow.com/a/13943171/1136132
    /**
     * Multi-array search
     *
     * @param array $array
     * @param array $search
     * @return array
     */
    private function multiArraySearch($array, $search)
    {
        // Create the result array
        $result = array();

        // Iterate over each array element
        foreach ($array as $key => $value) {
            // Iterate over each search condition
            foreach ($search as $k => $v) {
                // If the array element does not meet the search condition then continue to the next element
                if (!isset($value[$k]) || $value[$k] != $v) {
                    continue 2;
                }
            }

            // Add the array element's key to the result array
            //$result[] = $key;
            // There should be only one coincidence
            return $key;
        }

        // Return the result array
        //return $result;
        return null;
    }
}
