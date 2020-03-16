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

class AdminQuantityDiscountRulesController extends ModuleAdminController
{
    public function __construct()
    {
        include_once(_PS_MODULE_DIR_.'quantitydiscountpro/quantitydiscountpro.php');

        $this->bootstrap = true;
        $this->table = 'quantity_discount_rule';
        $this->className = 'QuantityDiscountRule';
        $this->lang = true;
        $this->addRowAction('edit');
        $this->addRowAction('delete');
        $this->_orderWay = 'DESC';

        parent::__construct();

        $this->bulk_actions = array(
            'delete' => array(
                'text' => $this->l('Delete selected'),
                'confirm' => $this->l('Delete selected items?'),
                'icon' => 'icon-trash'
            ),
        );

        $families = QuantityDiscountRuleFamily::getQuantityDiscountRuleFamilies();
        foreach ($families as $family) {
            $this->families_array[$family['id_quantity_discount_rule_family']] = $family['name'];
        }

        $this->fields_list = array(
            'id_quantity_discount_rule' => array(
                'title' => $this->l('ID'),
                'align' => 'center',
                'class' => 'fixed-width-xs'
            ),
            'name' => array(
                'title' => $this->l('Name')
            ),
            'id_family' => array(
                'title' => $this->l('Family'),
                'callback_object' => 'QuantityDiscountRuleFamily',
                'callback' => 'getFamilyName'
            ),
            'code' => array(
                'title' => $this->l('Code'),
                'align' => 'center',
            ),
            'code_prefix' => array(
                'title' => $this->l('Prefix'),
                'align' => 'center',
            ),
            'execute_other_rules' => array(
                'title' => $this->l('Execute other rules'),
                'type' => 'bool',
                'align' => 'center',
                'class' => 'fixed-width-xs',
                'callback' => 'printExecuteOtherRulesIcon',
            ),
            'priority' => array(
                'title' => $this->l('Priority'),
                'class' => 'fixed-width-sm',
                'align' => 'center',
                'search' => false,
            ),
            'active' => array(
                'title' => $this->l('Active'),
                'type' => 'bool',
                'callback' => 'printStatusIcon',
                'align' => 'center',
                'orderby' => false,
            ),
            'date_from' => array(
                'title' => $this->l('From date'),
                'type' => 'datetime'
            ),
            'date_to' => array(
                'title' => $this->l('To date'),
                'type' => 'datetime'
            ),
            'date_add' => array(
                'title' => $this->l('Valid'),
                'align' => 'center',
                'callback' => 'printValidIcon',
                'search' => false,
            ),
        );

        $this->condition_selectors = array('group', 'product', 'category', 'country', 'attribute', 'feature', 'zone', 'manufacturer', 'carrier', 'supplier', 'order_state', 'shop', 'gender', 'currency', 'state');
        $this->action_selectors = array('product', 'category', 'attribute', 'feature', 'manufacturer', 'supplier', 'carrier');
        $this->message_vars = array('hook_name', 'message');
    }

    public function printValidIcon($value, $object)
    {
        $today = date("Y-m-d H:i:s");
        $date_title = '';

        if ($object['date_from'] > $today) {
            $date_title = $this->l('Future rule');
            if ($object['date_from'] != "0000-00-00 00:00:00") {
                $date_title = $date_title.'. '.$this->l('Begins in:').' '.$object['date_from'];
            }
            if (version_compare(_PS_VERSION_, '1.6', '<')) {
                return '<span class="time-column future-date" title="'.$date_title.'"></span>';
            } else {
                return '<span class="time-column future-date-icon" title="'.$date_title.'"><i class="icon-time"></i></span>';
            }
        }

        if ($object['date_to'] == "0000-00-00 00:00:00" || $today < $object['date_to']) {
            $date_title = $this->l('Valid rule');
            if ($object['date_from'] != "0000-00-00 00:00:00" && $object['date_to'] != "0000-00-00 00:00:00") {
                $date_title = $date_title.'. '.$this->l('From:').' '.$object['date_from'].'. '.$this->l('Until:').' '.$object['date_to'];
            } else if ($object['date_from'] != "0000-00-00 00:00:00" && $object['date_to'] == "0000-00-00 00:00:00") {
                $date_title = $date_title.'. '.$this->l('From:').' '.$object['date_from'].' ('.$this->l('no expires').')';
            } else if ($object['date_from'] == "0000-00-00 00:00:00" && $object['date_to'] != "0000-00-00 00:00:00") {
                $date_title = $date_title.'. '.$this->l('Until:').' '.$object['date_to'];
            } else {
                $date_title = $date_title.' ('.$this->l('no expires').')';
            }

            if (version_compare(_PS_VERSION_, '1.6', '<')) {
                return '<span class="time-column valid-date" title="'.$date_title.'"></span>';
            } else {
                return '<span class="time-column valid-date-icon" title="'.$date_title.'"><i class="icon-time"></i></span>';
            }
        } else {
            $date_title = $this->l('Expired rule');
            if ($object['date_from'] != "0000-00-00 00:00:00" && $object['date_to'] != "0000-00-00 00:00:00") {
                $date_title = $date_title.'. '.$this->l('Between:').' '.$object['date_from'].' '.$this->l('and:').' '.$object['date_to'];
            } else {
                $date_title = $date_title.'. '.$this->l('From:').' '.$object['date_to'];
            }
            if (version_compare(_PS_VERSION_, '1.6', '<')) {
                return '<span class="time-column expired-date" title="'.$date_title.'"></span>';
            } else {
                return '<span class="time-column expired-date-icon" title="'.$date_title.'"><i class="icon-time"></i></span>';
            }
        }
    }

    public function printExecuteOtherRulesIcon($value, $quantityDiscountRule)
    {
        if (version_compare(_PS_VERSION_, '1.6', '>=')) {
            return '<a class="list-action-enable '.($value ? 'action-enabled' : 'action-disabled').'" href="index.php?'.htmlspecialchars('tab='.$this->controller_name.'&id_quantity_discount_rule='.(int)$quantityDiscountRule['id_quantity_discount_rule'].'&changeExecuteOtherRulesVal&token='.Tools::getAdminTokenLite('AdminQuantityDiscountRules')).'">'.($value ? '<i class="icon-check"></i>' : '<i class="icon-remove"></i>').'</a>';
        } else {
            return '<a href="index.php?tab='.$this->controller_name.'&id_quantity_discount_rule='.(int)$quantityDiscountRule['id_quantity_discount_rule'].'&changeExecuteOtherRulesVal&token='.Tools::getAdminTokenLite('AdminQuantityDiscountRules').'">
                '.($value ? '<img src="../img/admin/enabled.gif" />' : '<img src="../img/admin/disabled.gif" />').
            '</a>';
        }
    }

    public function printStatusIcon($value, $quantityDiscountRule)
    {
        if (version_compare(_PS_VERSION_, '1.6', '>=')) {
            return '<a class="list-action-enable '.($value ? 'action-enabled' : 'action-disabled').'" href="index.php?'.htmlspecialchars('tab='.$this->controller_name.'&id_quantity_discount_rule='.(int)$quantityDiscountRule['id_quantity_discount_rule'].'&changeStatusVal&token='.Tools::getAdminTokenLite('AdminQuantityDiscountRules')).'">'.($value ? '<i class="icon-check"></i>' : '<i class="icon-remove"></i>').'</a>';
        } else {
            return '<a href="index.php?tab='.$this->controller_name.'&id_quantity_discount_rule='.(int)$quantityDiscountRule['id_quantity_discount_rule'].'&changeStatusVal&token='.Tools::getAdminTokenLite('AdminQuantityDiscountRules').'">
                '.($value ? '<img src="../img/admin/enabled.gif" />' : '<img src="../img/admin/disabled.gif" />').
            '</a>';
        }
    }

    public function processChangeExecuteOtherRulesVal()
    {
        $quantityDiscountRule = new QuantityDiscountRule($this->id_object);
        if (!Validate::isLoadedObject($quantityDiscountRule)) {
            $this->errors[] = Tools::displayError('An error occurred while updating rule.');
        }

        $quantityDiscountRule->execute_other_rules = $quantityDiscountRule->execute_other_rules ? 0 : 1;
        if (!$quantityDiscountRule->update()) {
            $this->errors[] = Tools::displayError('An error occurred while updating rule.');
        }

        Tools::redirectAdmin(self::$currentIndex.'&token='.$this->token);
    }

    public function processChangeStatusVal()
    {

        $quantityDiscountRule = new QuantityDiscountRule($this->id_object);
        if (!Validate::isLoadedObject($quantityDiscountRule)) {
            $this->errors[] = Tools::displayError('An error occurred while updating rule.');
        }

        $quantityDiscountRule->active = $quantityDiscountRule->active ? 0 : 1;
        if (!$quantityDiscountRule->active) {
            QuantityDiscountRule::removeUnusedRules((int)$this->id_object);
        }
        if (!$quantityDiscountRule->update()) {
            $this->errors[] = Tools::displayError('An error occurred while updating rule.');
        }
        Tools::redirectAdmin(self::$currentIndex.'&token='.$this->token);
    }

    public function setMedia($isNewTheme = false)
    {
        parent::setMedia($isNewTheme);

        $this->addJqueryPlugin(array('autocomplete'));
        $this->addJqueryUI('ui.datepicker');
        $this->addJqueryUI('ui.button');
        $this->addJqueryUI('ui.sortable');
        $this->addJqueryUI('ui.droppable');

        if (version_compare(_PS_VERSION_, '1.6', '>=')) {
            $this->addCSS(_MODULE_DIR_.'quantitydiscountpro/views/css/admin.css');
        } else {
            $this->addCSS(_MODULE_DIR_.'quantitydiscountpro/views/css/admin-15.css');
        }
    }

    public function initPageHeaderToolbar()
    {
        if (empty($this->display)) {
            $this->page_header_toolbar_btn['new_quantity_discount_rule'] = array(
                'href' => self::$currentIndex.'&addquantity_discount_rule&token='.$this->token,
                'desc' => $this->l('Add new rule', null, null, false),
                'icon' => 'process-icon-new'
            );

            $this->page_header_toolbar_btn['edit_quantity_discount_rule_family'] = array(
                'href' => $this->context->link->getAdminLink('AdminQuantityDiscountRulesFamilies'),
                'desc' => $this->l('Edit families', null, null, false),
                'icon' => 'process-icon-edit'
            );

            $this->page_header_toolbar_btn['desc-module-translate'] = array(
                'href' => '#',
                'desc' => $this->l('Translate'),
                'modal_target' => '#moduleTradLangSelect',
                'icon' => 'process-icon-flag'
            );
        }

        parent::initPageHeaderToolbar();

        $this->context->smarty->clearAssign('help_link', '');
    }

    public function initToolbar()
    {
        parent::initToolbar();

        if (empty($this->display)) {
            $this->toolbar_btn['edit_quantity_discount_rule_family'] = array(
                'href' => $this->context->link->getAdminLink('AdminQuantityDiscountRulesFamilies'),
                'desc' => $this->l('Edit families', null, null, false),
                'imgclass' => 'edit'
            );
        }
    }

    public function initProcess()
    {
        parent::initProcess();

        if (version_compare(_PS_VERSION_, '1.7', '<')) {
            if (Tools::isSubmit('changeExecuteOtherRulesVal') && $this->id_object) {
                if ($this->tabAccess['edit'] === '1') {
                    $this->action = 'change_execute_other_rules_val';
                } else {
                    $this->errors[] = Tools::displayError('You do not have permission to edit this.');
                }
            } elseif (Tools::isSubmit('changeStatusVal') && $this->id_object) {
                if ($this->tabAccess['edit'] === '1') {
                    $this->action = 'change_status_val';
                } else {
                    $this->errors[] = Tools::displayError('You do not have permission to edit this.');
                }
            } elseif (Tools::getIsset('duplicate'.$this->table)) {
                if ($this->tabAccess['add'] === '1') {
                    $this->action = 'duplicate';
                } else {
                    $this->errors[] = Tools::displayError('You do not have permission to add this.');
                }
            }
        } else {
            if (Tools::isSubmit('changeExecuteOtherRulesVal') && $this->id_object) {
                if ($this->access('edit')) {
                    $this->action = 'change_execute_other_rules_val';
                } else {
                    $this->errors[] = Tools::displayError('You do not have permission to edit this.');
                }
            } elseif (Tools::isSubmit('changeStatusVal') && $this->id_object) {
                if ($this->access('edit')) {
                    $this->action = 'change_status_val';
                } else {
                    $this->errors[] = Tools::displayError('You do not have permission to edit this.');
                }
            } elseif (Tools::getIsset('duplicate'.$this->table)) {
                if ($this->access('add')) {
                    $this->action = 'duplicate';
                } else {
                    $this->errors[] = Tools::displayError('You do not have permission to add this.');
                }
            }
        }
    }

    public function initContent()
    {
        //In case of error in installation, create the tables
        $sql = 'SHOW TABLES LIKE \''._DB_PREFIX_.'quantity_discount_rule\'';
        if ((version_compare(_PS_VERSION_, '1.5.0.13', '<') && Module::isInstalled('quantitydiscountpro') && !Db::getInstance()->executeS($sql))
            || (version_compare(_PS_VERSION_, '1.5.0.13', '>=') && Module::isEnabled('quantitydiscountpro') && !Db::getInstance()->executeS($sql))) {
            QuantityDiscountDatabase::createTables();
        }

        //if there are warnings, don't display the form until they are fixed
        if ($warnings = $this->module->getWarnings(false)) {
            $this->errors[] = Tools::displayError($warnings);
            return;
        }

        //Redirect to family controller if there isn't any family created
        if (count(QuantityDiscountRuleFamily::getQuantityDiscountRuleFamilies(false)) == 0) {
            $this->redirect_after = $this->context->link->getAdminLink('AdminQuantityDiscountRulesFamilies');
            $this->redirect();
        }

        parent::initContent();

        if (!$this->display) {
            $this->content .= $this->module->getQuantityDiscountRules();
        }

        if (version_compare(_PS_VERSION_, '1.6', '>=')) {
            $this->context->smarty->assign(array(
                'this_path'     => $this->module->getPathUri(),
                'support_id'    => '9129'
            ));

            $available_lang_codes = array('en', 'es', 'fr', 'it', 'de');
            $default_lang_code = 'en';
            $template_iso_suffix = in_array(strtok($this->context->language->language_code, '-'), $available_lang_codes) ? strtok($this->context->language->language_code, '-') : $default_lang_code;
            $this->content .= $this->context->smarty->fetch($this->module->getLocalPath().'views/templates/admin/company/information_'.$template_iso_suffix.'.tpl');
        }

        $this->context->smarty->assign(array(
            'content' => $this->content,
        ));
    }

    public function initModal()
    {
        parent::initModal();

        $languages = Language::getLanguages(false);
        $translateLinks = array();

        if (version_compare(_PS_VERSION_, '1.7.2.1', '>=')) {
            $isNewTranslateSystem = $this->module->isUsingNewTranslationSystem();
            $link = Context::getContext()->link;
            foreach ($languages as $lang) {
                if ($isNewTranslateSystem) {
                    $translateLinks[$lang['iso_code']] = $link->getAdminLink('AdminTranslationSf', true, array(
                        'lang' => $lang['iso_code'],
                        'type' => 'modules',
                        'selected' => $this->module->name,
                        'locale' => $lang['locale'],
                    ));
                } else {
                    $translateLinks[$lang['iso_code']] = $link->getAdminLink('AdminTranslations', true, array(), array(
                        'type' => 'modules',
                        'module' => $this->module->name,
                        'lang' => $lang['iso_code'],
                    ));
                }
            }
        }

        $this->context->smarty->assign(array(
            'trad_link' => 'index.php?tab=AdminTranslations&token='.Tools::getAdminTokenLite('AdminTranslations').'&type=modules&module='.$this->module->name.'&lang=',
            'module_languages' => $languages,
            'module_name' => $this->module->name,
            'translateLinks' => $translateLinks,
        ));

        $modal_content = $this->context->smarty->fetch('controllers/modules/modal_translation.tpl');

        $this->modals[] = array(
            'modal_id' => 'moduleTradLangSelect',
            'modal_class' => 'modal-sm',
            'modal_title' => $this->l('Translate this module'),
            'modal_content' => $modal_content
        );
    }

    public function postProcess()
    {
        if (count($_POST, COUNT_RECURSIVE) >= ini_get("max_input_vars")) {
            $this->errors[] = Tools::displayError('Warning! Your PHP configuration limits the maximum number of fields allowed in a form and the rule has not been saved correctly. Please ask your hosting provider to increase the variable max_input_vars more than '.count($_POST, COUNT_RECURSIVE).'.');
        }

        if (Tools::isSubmit('submitAddquantity_discount_rule') || Tools::isSubmit('submitAddquantity_discount_ruleAndStay')) {
            if (!Tools::getValue('id_family')) {
                $this->errors[] = Tools::displayError('You must assign this rule to a family.');
            }

            if (!Tools::getValue('code') && !Tools::getValue('code_prefix')) {
                $this->errors[] = Tools::displayError('You must define a code or a code prefix.');
            }

            if (Tools::strlen(Tools::getValue('code_prefix')) > 242) {
                $this->errors[] = Tools::displayError('Code prefix is too long.');
            }

            //Check if the code is used by a regular cart rule
            if (Tools::getValue('code') && CartRule::getIdByCode(Tools::getValue('code')) && !QuantityDiscountRule::cartRuleGeneratedByAQuantityDiscountRuleCode(Tools::getValue('code'))) {
                $this->errors[] = Tools::displayError('You can\'t use this code. There is a cart rule already generated with it.');
            }

            if (strtotime(Tools::getValue('date_from')) > strtotime(Tools::getValue('date_to'))) {
                $this->errors[] = Tools::displayError('The voucher cannot end before it begins.');
            }

            $modules_exceptions = array_filter(array_map('trim', explode(';', Tools::getValue('modules_exceptions'))));

            if (!empty($modules_exceptions)) {
                foreach ($modules_exceptions as $modules_exception) {
                    if (!Validate::isModuleName($modules_exception)) {
                        $this->errors[] = Tools::displayError('Please check module exceptions.');
                    }
                }
            }

            $_POST['modules_exceptions'] = implode(";", array_map("trim", array_filter($modules_exceptions)));

            //Validate conditions
            //Get $_POST vars
            $form_values = array();
            $definition = ObjectModel::getDefinition('quantityDiscountRuleCondition');
            foreach (array_keys($definition['fields']) as $condition_var) {
                $form_values[$condition_var] = Tools::getValue('condition_'.$condition_var);
            }

            foreach ($this->condition_selectors as $condition_var) {
                $form_values['condition_'.$condition_var.'_select'] = Tools::getValue('condition_'.$condition_var.'_select_json');
            }

            if ($form_values['id_type'] && array_filter($form_values['id_type'])) {
                //Foreach condition group
                foreach (array_keys($form_values['id_type']) as $quantity_discount_rule_group) {
                    //Foreach condition of a condition group
                    foreach (array_keys($form_values['id_type'][$quantity_discount_rule_group]) as $quantity_discount_rule_group_rule) {
                        $id_type = (int)$form_values['id_type'][$quantity_discount_rule_group][$quantity_discount_rule_group_rule];
                        if ($id_type) {
                            switch ((int)$id_type) {
                                // Limit to a single customer
                                // Validate if user introduced customer
                                case 1:
                                    if (!(int)$form_values['id_customer'][$quantity_discount_rule_group][$quantity_discount_rule_group_rule]) {
                                        $this->errors[] = Tools::displayError('You must select a customer');
                                    }
                                    break;
                                // Customer signed up between a date
                                // Validate if dates are valid
                                case 3:
                                    if (($form_values['customer_signedup_date_from'][$quantity_discount_rule_group][$quantity_discount_rule_group_rule] == '0000-00-00 00:00:00' || !$form_values['customer_signedup_date_from'][$quantity_discount_rule_group][$quantity_discount_rule_group_rule])
                                        && ($form_values['customer_signedup_date_to'][$quantity_discount_rule_group][$quantity_discount_rule_group_rule] == '0000-00-00 00:00:00' || !$form_values['customer_signedup_date_to'][$quantity_discount_rule_group][$quantity_discount_rule_group_rule] == '0000-00-00 00:00:00')) {
                                        $this->errors[] = Tools::displayError('You must define customer signed up date interval.');
                                    }

                                    if (strtotime($form_values['customer_signedup_date_from'][$quantity_discount_rule_group][$quantity_discount_rule_group_rule]) > strtotime($form_values['customer_signedup_date_to'][$quantity_discount_rule_group][$quantity_discount_rule_group_rule])) {
                                        $this->errors[] = Tools::displayError('The customer signed up date cannot end before it begins.');
                                    }
                                    break;
                                //Customer and orders
                                case 4:
                                    if (($form_values['customer_orders_nb_date_from'][$quantity_discount_rule_group][$quantity_discount_rule_group_rule] == '0000-00-00 00:00:00' || !$form_values['customer_orders_nb_date_from'][$quantity_discount_rule_group][$quantity_discount_rule_group_rule])
                                        && ($form_values['customer_orders_nb_date_to'][$quantity_discount_rule_group][$quantity_discount_rule_group_rule] == '0000-00-00 00:00:00' || !$form_values['customer_orders_nb_date_to'][$quantity_discount_rule_group][$quantity_discount_rule_group_rule] == '0000-00-00 00:00:00')
                                        && !(int)$form_values['customer_orders_nb_days'][$quantity_discount_rule_group][$quantity_discount_rule_group_rule]) {
                                        $this->errors[] = Tools::displayError('You must define some restriction at condition "Customer and orders done".');
                                    }

                                    if (strtotime($form_values['customer_orders_nb_date_from'][$quantity_discount_rule_group][$quantity_discount_rule_group_rule]) > strtotime($form_values['customer_orders_nb_date_to'][$quantity_discount_rule_group][$quantity_discount_rule_group_rule])) {
                                        $this->errors[] = Tools::displayError('The customer orders done interval date cannot end before it begins.');
                                    }

                                    if (!array_filter($form_values['condition_order_state_select'][$quantity_discount_rule_group][$quantity_discount_rule_group_rule])) {
                                        $this->errors[] = Tools::displayError('You have to select at least 1 order state');
                                    }

                                    break;
                                //Customer and amount spent
                                case 5:
                                    if (($form_values['customer_orders_amount_date_from'][$quantity_discount_rule_group][$quantity_discount_rule_group_rule] == '0000-00-00 00:00:00' || !$form_values['customer_orders_amount_date_from'][$quantity_discount_rule_group][$quantity_discount_rule_group_rule])
                                        && ($form_values['customer_orders_amount_date_to'][$quantity_discount_rule_group][$quantity_discount_rule_group_rule] == '0000-00-00 00:00:00' || !$form_values['customer_orders_amount_date_to'][$quantity_discount_rule_group][$quantity_discount_rule_group_rule] == '0000-00-00 00:00:00')
                                        && !(int)$form_values['customer_orders_amount_days'][$quantity_discount_rule_group][$quantity_discount_rule_group_rule]
                                        && !(int)$form_values['customer_orders_amount_interval'][$quantity_discount_rule_group][$quantity_discount_rule_group_rule]) {
                                        $this->errors[] = Tools::displayError('You must define some restriction at condition "Customer and amount spent".');
                                    }

                                    if (strtotime($form_values['customer_orders_amount_date_from'][$quantity_discount_rule_group][$quantity_discount_rule_group_rule]) > strtotime($form_values['customer_orders_amount_date_to'][$quantity_discount_rule_group][$quantity_discount_rule_group_rule])) {
                                        $this->errors[] = Tools::displayError('The customer amount spent interval date cannot end before it begins.');
                                    }

                                    if (!array_filter($form_values['condition_order_state_select'][$quantity_discount_rule_group][$quantity_discount_rule_group_rule])) {
                                        $this->errors[] = Tools::displayError('You have to select at least 1 order state');
                                    }

                                    if ((int)$form_values['customer_orders_amount_days'][$quantity_discount_rule_group][$quantity_discount_rule_group_rule]
                                        && (($form_values['customer_orders_amount_date_from'][$quantity_discount_rule_group][$quantity_discount_rule_group_rule] &&
                                            $form_values['customer_orders_amount_date_from'][$quantity_discount_rule_group][$quantity_discount_rule_group_rule] != '0000-00-00 00:00:00')
                                            || ($form_values['customer_orders_amount_date_to'][$quantity_discount_rule_group][$quantity_discount_rule_group_rule] &&
                                                $form_values['customer_orders_amount_date_to'][$quantity_discount_rule_group][$quantity_discount_rule_group_rule] != '0000-00-00 00:00:00'))) {
                                        $this->errors[] = Tools::displayError('You can not define more than 1 interval');
                                    }

                                    if ((int)$form_values['customer_orders_amount_days'][$quantity_discount_rule_group][$quantity_discount_rule_group_rule]
                                        && (int)$form_values['customer_orders_amount_interval'][$quantity_discount_rule_group][$quantity_discount_rule_group_rule]) {
                                        $this->errors[] = Tools::displayError('You can not define more than 1 interval');
                                    }

                                    if ((($form_values['customer_orders_amount_date_from'][$quantity_discount_rule_group][$quantity_discount_rule_group_rule] != '0000-00-00 00:00:00' || !$form_values['customer_orders_amount_date_from'][$quantity_discount_rule_group][$quantity_discount_rule_group_rule])
                                            || ($form_values['customer_orders_amount_date_to'][$quantity_discount_rule_group][$quantity_discount_rule_group_rule] != '0000-00-00 00:00:00' || !$form_values['customer_orders_amount_date_to'][$quantity_discount_rule_group][$quantity_discount_rule_group_rule]))
                                        && (int)$form_values['customer_orders_amount_interval'][$quantity_discount_rule_group][$quantity_discount_rule_group_rule]) {
                                        $this->errors[] = Tools::displayError('You can not define more than 1 interval');
                                    }

                                    break;

                                //Products in the cart
                                case 10:
                                    if ($form_values['products_amount'][$quantity_discount_rule_group][$quantity_discount_rule_group_rule] == 0
                                        && $form_values['products_nb'][$quantity_discount_rule_group][$quantity_discount_rule_group_rule] == 0
                                        && $form_values['products_nb_dif'][$quantity_discount_rule_group][$quantity_discount_rule_group_rule] == 0
                                        && $form_values['products_nb_dif_cat'][$quantity_discount_rule_group][$quantity_discount_rule_group_rule] == 0) {
                                        $this->errors[] = Tools::displayError('You must define at least 1 condition');
                                    }

                                    if ($form_values['filter_by_product'][$quantity_discount_rule_group][$quantity_discount_rule_group_rule]
                                        && !array_filter($form_values['condition_product_select'][$quantity_discount_rule_group][$quantity_discount_rule_group_rule])) {
                                        $this->errors[] = Tools::displayError('If you filter by product, you have to select at least 1 product');
                                    }

                                    if ($form_values['filter_by_attribute'][$quantity_discount_rule_group][$quantity_discount_rule_group_rule]
                                        && !array_filter($form_values['condition_attribute_select'][$quantity_discount_rule_group][$quantity_discount_rule_group_rule])) {
                                        $this->errors[] = Tools::displayError('If you filter by attribute, you have to select at least 1 attribute');
                                    }

                                    if ($form_values['filter_by_feature'][$quantity_discount_rule_group][$quantity_discount_rule_group_rule]
                                        && !array_filter($form_values['condition_feature_select'][$quantity_discount_rule_group][$quantity_discount_rule_group_rule])) {
                                        $this->errors[] = Tools::displayError('If you filter by feature, you have to select at least 1 feature');
                                    }

                                    if ($form_values['filter_by_category'][$quantity_discount_rule_group][$quantity_discount_rule_group_rule]
                                        && !array_filter($form_values['condition_category_select'][$quantity_discount_rule_group][$quantity_discount_rule_group_rule])) {
                                        $this->errors[] = Tools::displayError('If you filter by category, you have to select at least 1 category');
                                    }

                                    if ($form_values['filter_by_supplier'][$quantity_discount_rule_group][$quantity_discount_rule_group_rule]
                                        && !array_filter($form_values['condition_supplier_select'][$quantity_discount_rule_group][$quantity_discount_rule_group_rule])) {
                                        $this->errors[] = Tools::displayError('If you filter by supplier, you have to select at least 1 supplier');
                                    }

                                    if ($form_values['filter_by_manufacturer'][$quantity_discount_rule_group][$quantity_discount_rule_group_rule]
                                        && !array_filter($form_values['condition_manufacturer_select'][$quantity_discount_rule_group][$quantity_discount_rule_group_rule])) {
                                        $this->errors[] = Tools::displayError('If you filter by manufacturer, you have to select at least 1 manufacturer');
                                    }

                                    break;

                                //Delivery country
                                case 11:
                                    if (!array_filter($form_values['condition_country_select'][$quantity_discount_rule_group][$quantity_discount_rule_group_rule])) {
                                        $this->errors[] = Tools::displayError('You have to select at least 1 country');
                                    }

                                    break;

                                //Carrier
                                case 12:
                                    if (!array_filter($form_values['condition_carrier_select'][$quantity_discount_rule_group][$quantity_discount_rule_group_rule])) {
                                        $this->errors[] = Tools::displayError('You have to select at least 1 carrier');
                                    }

                                    break;

                                //Group selection
                                case 13:
                                    if (!array_filter($form_values['condition_group_select'][$quantity_discount_rule_group][$quantity_discount_rule_group_rule])) {
                                        $this->errors[] = Tools::displayError('You have to select at least 1 group');
                                    }

                                    break;

                                //Zone
                                case 18:
                                    if (!array_filter($form_values['condition_zone_select'][$quantity_discount_rule_group][$quantity_discount_rule_group_rule])) {
                                        $this->errors[] = Tools::displayError('You have to select at least 1 carrier');
                                    }

                                    break;

                                //Membership
                                case 19:
                                    if (!(int)$form_values['customer_membership'][$quantity_discount_rule_group][$quantity_discount_rule_group_rule]) {
                                        $this->errors[] = Tools::displayError('You must select a period of membership');
                                    }

                                    break;

                                //Customer gender
                                case 21:
                                    if (!array_filter($form_values['condition_gender_select'][$quantity_discount_rule_group][$quantity_discount_rule_group_rule])) {
                                        $this->errors[] = Tools::displayError('You have to select at least 1 gender');
                                    }

                                    break;

                                //Currency
                                case 22:
                                    if (!array_filter($form_values['condition_currency_select'][$quantity_discount_rule_group][$quantity_discount_rule_group_rule])) {
                                        $this->errors[] = Tools::displayError('You have to select at least 1 currency');
                                    }

                                    break;

                                //Customer age
                                case 23:
                                    if (!(int)$form_values['customer_years_from'][$quantity_discount_rule_group][$quantity_discount_rule_group_rule]) {
                                        $this->errors[] = Tools::displayError('You must introduce a correct age');
                                    }

                                    if (!(int)$form_values['customer_years_to'][$quantity_discount_rule_group][$quantity_discount_rule_group_rule]) {
                                        $this->errors[] = Tools::displayError('You must introduce a correct age');
                                    }

                                    break;

                                // Day of the week
                                case 26:
                                    break;
                            }
                        }
                    }
                }
            }

            //Validate actions
            $form_values = array();
            $definition = ObjectModel::getDefinition('quantityDiscountRuleAction');
            foreach (array_keys($definition['fields']) as $action_var) {
                $form_values[$action_var] = Tools::getValue('action_'.$action_var);
            }

            foreach ($this->action_selectors as $action_var) {
                $form_values['action_'.$action_var.'_select'] = Tools::getValue('action_'.$action_var.'_select_json');
            }

            $actions = false;
            $actionBuyX = false;
            $actionGetY = false;

            if ($form_values['id_type'] && array_filter($form_values['id_type'])) {
                foreach (array_keys($form_values['id_type']) as $quantity_discount_rule_action) {
                    $id_type = (int)$form_values['id_type'][$quantity_discount_rule_action];
                    if ((int)$id_type) {
                        $actions = true;
                        switch ((int)$id_type) {
                            /**
                             *
                             * Shipping cost - Fixed discount
                             *
                             **/
                            case 1:
                                if (!Validate::isPrice($form_values['reduction_amount'][$quantity_discount_rule_action])
                                    || !($form_values['reduction_amount'][$quantity_discount_rule_action] > 0)) {
                                    $this->errors[] = Tools::displayError('You must define a valid reduction amount.');
                                }

                                break;

                            /**
                             *
                             * Shipping cost - Percentage discount
                             *
                             */
                            case 5:
                                if (!$form_values['reduction_percent'][$quantity_discount_rule_action] > 0
                                    || !Validate::isPercentage((float)str_replace(',', '.', $form_values['reduction_percent'][$quantity_discount_rule_action]))) {
                                    $this->errors[] = Tools::displayError('You must define a valid percent reduction.');
                                }

                                if ($form_values['reduction_max_amount'][$quantity_discount_rule_action]
                                    && !Validate::isPrice($form_values['reduction_max_amount'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('You must define a valid maximum reduction amount.');
                                }

                                break;

                            /**
                             *
                             * Order amount - Fixed discount
                             *
                             */
                            case 2:
                                if (!Validate::isPrice($form_values['reduction_amount'][$quantity_discount_rule_action])
                                    || !($form_values['reduction_amount'][$quantity_discount_rule_action] > 0)) {
                                    $this->errors[] = Tools::displayError('You must define a valid reduction amount.');
                                }

                                break;

                            /**
                             *
                             * Order amount - Percentage discount
                             *
                             */
                            case 3:
                                if (!$form_values['reduction_percent'][$quantity_discount_rule_action] > 0
                                    || !Validate::isPercentage((float)str_replace(',', '.', $form_values['reduction_percent'][$quantity_discount_rule_action]))) {
                                    $this->errors[] = Tools::displayError('You must define a valid percent reduction.');
                                }

                                if ($form_values['reduction_max_amount'][$quantity_discount_rule_action]
                                    && !Validate::isPrice($form_values['reduction_max_amount'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('You must define a valid maximum reduction amount.');
                                }

                                break;

                            /**
                             *
                             * Buy X - Get Y with fixed discount
                             *
                             */
                            case 6:
                                if (!$form_values['group_products_by'][$quantity_discount_rule_action]) {
                                    $this->errors[] = Tools::displayError('You must define how to group products.');
                                }

                                if (!(int)$form_values['products_nb_each'][$quantity_discount_rule_action]) {
                                    $this->errors[] = Tools::displayError('You must define the number of products to buy.');
                                } else if (!(int)$form_values['apply_discount_to_nb'][$quantity_discount_rule_action]) {
                                    $this->errors[] = Tools::displayError('You must define the number of products to apply discount.');
                                } else if ((int)$form_values['products_nb_each'][$quantity_discount_rule_action] < (int)$form_values['apply_discount_to_nb'][$quantity_discount_rule_action]) {
                                    $this->errors[] = Tools::displayError('You can\'t apply a discount to more products than purchased.');
                                }

                                if (!Validate::isPrice($form_values['reduction_amount'][$quantity_discount_rule_action])
                                    || !($form_values['reduction_amount'][$quantity_discount_rule_action] > 0)) {
                                    $this->errors[] = Tools::displayError('You must define a valid reduction amount.');
                                }

                                if ($form_values['nb_repetitions'][$quantity_discount_rule_action] == 'custom'
                                    && !($form_values['nb_repetitions_custom'][$quantity_discount_rule_action] > 0)) {
                                    $this->errors[] = Tools::displayError('You must define the number of repetitions.');
                                }

                                if ($form_values['filter_by_product'][$quantity_discount_rule_action]
                                    && !array_filter($form_values['action_product_select'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('If you filter by product, you have to select at least 1 product');
                                }

                                if ($form_values['filter_by_attribute'][$quantity_discount_rule_action]
                                    && !array_filter($form_values['action_attribute_select'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('If you filter by attribute, you have to select at least 1 attribute');
                                }

                                if ($form_values['filter_by_feature'][$quantity_discount_rule_action]
                                    && !array_filter($form_values['action_feature_select'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('If you filter by feature, you have to select at least 1 feature');
                                }

                                if ($form_values['filter_by_category'][$quantity_discount_rule_action]
                                    && !array_filter($form_values['action_category_select'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('If you filter by category, you have to select at least 1 category');
                                }

                                if ($form_values['filter_by_supplier'][$quantity_discount_rule_action]
                                    && !array_filter($form_values['action_supplier_select'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('If you filter by supplier, you have to select at least 1 supplier');
                                }

                                if ($form_values['filter_by_manufacturer'][$quantity_discount_rule_action]
                                    && !array_filter($form_values['action_manufacturer_select'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('If you filter by manufacturer, you have to select at least 1 manufacturer');
                                }

                                break;

                            /**
                             *
                             * Buy X - Get Y with percentage discount
                             *
                             */
                            case 7:
                                if (!$form_values['group_products_by'][$quantity_discount_rule_action]) {
                                    $this->errors[] = Tools::displayError('You must define how to group products.');
                                }

                                if (!(int)$form_values['products_nb_each'][$quantity_discount_rule_action]) {
                                    $this->errors[] = Tools::displayError('You must define the number of products to buy.');
                                } else if (!(int)$form_values['apply_discount_to_nb'][$quantity_discount_rule_action]) {
                                    $this->errors[] = Tools::displayError('You must define the number of products to apply discount.');
                                } else if ((int)$form_values['products_nb_each'][$quantity_discount_rule_action] < (int)$form_values['apply_discount_to_nb'][$quantity_discount_rule_action]) {
                                    $this->errors[] = Tools::displayError('You can\'t apply a discount to more products than purchased.');
                                }

                                if (!$form_values['reduction_percent'][$quantity_discount_rule_action] > 0
                                    || !Validate::isPercentage((float)str_replace(',', '.', $form_values['reduction_percent'][$quantity_discount_rule_action]))) {
                                    $this->errors[] = Tools::displayError('You must define a valid percent reduction.');
                                }

                                if ($form_values['reduction_max_amount'][$quantity_discount_rule_action]
                                    && !Validate::isPrice($form_values['reduction_max_amount'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('You must define a valid maximum reduction amount.');
                                }

                                if ($form_values['reduction_product_max_amount'][$quantity_discount_rule_action]
                                    && !Validate::isPrice($form_values['reduction_product_max_amount'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('You must define a valid maximum reduction amount.');
                                }

                                if ($form_values['nb_repetitions'][$quantity_discount_rule_action] == 'custom'
                                    && !($form_values['nb_repetitions_custom'][$quantity_discount_rule_action] > 0)) {
                                    $this->errors[] = Tools::displayError('You must define the number of repetitions.');
                                }

                                if ($form_values['filter_by_product'][$quantity_discount_rule_action]
                                    && !array_filter($form_values['action_product_select'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('If you filter by product, you have to select at least 1 product');
                                }

                                if ($form_values['filter_by_attribute'][$quantity_discount_rule_action]
                                    && !array_filter($form_values['action_attribute_select'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('If you filter by attribute, you have to select at least 1 attribute');
                                }

                                if ($form_values['filter_by_feature'][$quantity_discount_rule_action]
                                    && !array_filter($form_values['action_feature_select'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('If you filter by feature, you have to select at least 1 feature');
                                }

                                if ($form_values['filter_by_category'][$quantity_discount_rule_action]
                                    && !array_filter($form_values['action_category_select'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('If you filter by category, you have to select at least 1 category');
                                }

                                if ($form_values['filter_by_supplier'][$quantity_discount_rule_action]
                                    && !array_filter($form_values['action_supplier_select'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('If you filter by supplier, you have to select at least 1 supplier');
                                }

                                if ($form_values['filter_by_manufacturer'][$quantity_discount_rule_action]
                                    && !array_filter($form_values['action_manufacturer_select'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('If you filter by manufacturer, you have to select at least 1 manufacturer');
                                }

                                break;

                            /**
                             *
                             * Buy X - Get Y with fixed price
                             *
                             */
                            case 8:
                                if (!$form_values['group_products_by'][$quantity_discount_rule_action]) {
                                    $this->errors[] = Tools::displayError('You must define how to group products.');
                                }

                                if (!(int)$form_values['products_nb_each'][$quantity_discount_rule_action]) {
                                    $this->errors[] = Tools::displayError('You must define the number of products to buy.');
                                } else if (!(int)$form_values['apply_discount_to_nb'][$quantity_discount_rule_action]) {
                                    $this->errors[] = Tools::displayError('You must define the number of products to apply discount.');
                                } else if ((int)$form_values['products_nb_each'][$quantity_discount_rule_action] < (int)$form_values['apply_discount_to_nb'][$quantity_discount_rule_action]) {
                                    $this->errors[] = Tools::displayError('You can\'t apply a discount to more products than purchased.');
                                }

                                if (!($form_values['reduction_amount'][$quantity_discount_rule_action] > 0)
                                    || !Validate::isPrice($form_values['reduction_amount'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('You must define a valid fixed amount.');
                                }

                                if ($form_values['nb_repetitions'][$quantity_discount_rule_action] == 'custom'
                                    && !($form_values['nb_repetitions_custom'][$quantity_discount_rule_action] > 0)) {
                                    $this->errors[] = Tools::displayError('You must define the number of repetitions.');
                                }

                                if ($form_values['filter_by_product'][$quantity_discount_rule_action]
                                    && !array_filter($form_values['action_product_select'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('If you filter by product, you have to select at least 1 product');
                                }

                                if ($form_values['filter_by_attribute'][$quantity_discount_rule_action]
                                    && !array_filter($form_values['action_attribute_select'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('If you filter by attribute, you have to select at least 1 attribute');
                                }

                                if ($form_values['filter_by_feature'][$quantity_discount_rule_action]
                                    && !array_filter($form_values['action_feature_select'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('If you filter by feature, you have to select at least 1 feature');
                                }

                                if ($form_values['filter_by_category'][$quantity_discount_rule_action]
                                    && !array_filter($form_values['action_category_select'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('If you filter by category, you have to select at least 1 category');
                                }

                                if ($form_values['filter_by_supplier'][$quantity_discount_rule_action]
                                    && !array_filter($form_values['action_supplier_select'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('If you filter by supplier, you have to select at least 1 supplier');
                                }

                                if ($form_values['filter_by_manufacturer'][$quantity_discount_rule_action]
                                    && !array_filter($form_values['action_manufacturer_select'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('If you filter by manufacturer, you have to select at least 1 manufacturer');
                                }

                                break;

                            /**
                             *
                             * All products after N - Fixed discount
                             *
                             */
                            case 12:
                            case 14:
                                if (!$form_values['group_products_by'][$quantity_discount_rule_action]) {
                                    $this->errors[] = Tools::displayError('You must define how to group products.');
                                }

                                if (!Validate::isInt($form_values['products_nb_each'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('You must define the number of products to buy.');
                                }

                                if (!($form_values['reduction_amount'][$quantity_discount_rule_action] > 0)
                                    || !Validate::isPrice($form_values['reduction_amount'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('You must define a valid amount.');
                                }

                                if ($form_values['nb_repetitions'][$quantity_discount_rule_action] == 'custom'
                                    && !($form_values['nb_repetitions_custom'][$quantity_discount_rule_action] > 0)) {
                                    $this->errors[] = Tools::displayError('You must define the number of repetitions.');
                                }

                                if ($form_values['filter_by_product'][$quantity_discount_rule_action]
                                    && !array_filter($form_values['action_product_select'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('If you filter by product, you have to select at least 1 product');
                                }

                                if ($form_values['filter_by_attribute'][$quantity_discount_rule_action]
                                    && !array_filter($form_values['action_attribute_select'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('If you filter by attribute, you have to select at least 1 attribute');
                                }

                                if ($form_values['filter_by_feature'][$quantity_discount_rule_action]
                                    && !array_filter($form_values['action_feature_select'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('If you filter by feature, you have to select at least 1 feature');
                                }

                                if ($form_values['filter_by_category'][$quantity_discount_rule_action]
                                    && !array_filter($form_values['action_category_select'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('If you filter by category, you have to select at least 1 category');
                                }

                                if ($form_values['filter_by_supplier'][$quantity_discount_rule_action]
                                    && !array_filter($form_values['action_supplier_select'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('If you filter by supplier, you have to select at least 1 supplier');
                                }

                                if ($form_values['filter_by_manufacturer'][$quantity_discount_rule_action]
                                    && !array_filter($form_values['action_manufacturer_select'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('If you filter by manufacturer, you have to select at least 1 manufacturer');
                                }

                                break;

                            case 13:
                                if (!$form_values['group_products_by'][$quantity_discount_rule_action]) {
                                    $this->errors[] = Tools::displayError('You must define how to group products.');
                                }

                                if (!Validate::isInt($form_values['products_nb_each'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('You must define the number of products to buy.');
                                }

                                if (!$form_values['reduction_percent'][$quantity_discount_rule_action] > 0
                                    || !Validate::isPercentage((float)str_replace(',', '.', $form_values['reduction_percent'][$quantity_discount_rule_action]))) {
                                    $this->errors[] = Tools::displayError('You must define a valid percent reduction.');
                                }

                                if ($form_values['reduction_max_amount'][$quantity_discount_rule_action]
                                    && !Validate::isPrice($form_values['reduction_max_amount'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('You must define a valid maximum reduction amount.');
                                }

                                if ($form_values['reduction_product_max_amount'][$quantity_discount_rule_action]
                                    && !Validate::isPrice($form_values['reduction_product_max_amount'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('You must define a valid maximum reduction amount.');
                                }

                                if ($form_values['nb_repetitions'][$quantity_discount_rule_action] == 'custom'
                                    && !($form_values['nb_repetitions_custom'][$quantity_discount_rule_action] > 0)) {
                                    $this->errors[] = Tools::displayError('You must define the number of repetitions.');
                                }

                                if ($form_values['filter_by_product'][$quantity_discount_rule_action]
                                    && !array_filter($form_values['action_product_select'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('If you filter by product, you have to select at least 1 product');
                                }

                                if ($form_values['filter_by_attribute'][$quantity_discount_rule_action]
                                    && !array_filter($form_values['action_attribute_select'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('If you filter by attribute, you have to select at least 1 attribute');
                                }

                                if ($form_values['filter_by_feature'][$quantity_discount_rule_action]
                                    && !array_filter($form_values['action_feature_select'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('If you filter by feature, you have to select at least 1 feature');
                                }

                                if ($form_values['filter_by_category'][$quantity_discount_rule_action]
                                    && !array_filter($form_values['action_category_select'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('If you filter by category, you have to select at least 1 category');
                                }

                                if ($form_values['filter_by_supplier'][$quantity_discount_rule_action]
                                    && !array_filter($form_values['action_supplier_select'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('If you filter by supplier, you have to select at least 1 supplier');
                                }

                                if ($form_values['filter_by_manufacturer'][$quantity_discount_rule_action]
                                    && !array_filter($form_values['action_manufacturer_select'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('If you filter by manufacturer, you have to select at least 1 manufacturer');
                                }

                                break;

                            case 16:
                                if (!$form_values['group_products_by'][$quantity_discount_rule_action]) {
                                    $this->errors[] = Tools::displayError('You must define how to group products.');
                                }

                                if (!Validate::isInt($form_values['products_nb_each'][$quantity_discount_rule_action])
                                    || !($form_values['products_nb_each'][$quantity_discount_rule_action] > 1)) {
                                    $this->errors[] = Tools::displayError('You must define the number of products to buy.');
                                }

                                if (!$form_values['reduction_percent'][$quantity_discount_rule_action] > 0
                                    || !Validate::isPercentage((float)str_replace(',', '.', $form_values['reduction_percent'][$quantity_discount_rule_action]))) {
                                    $this->errors[] = Tools::displayError('You must define a valid percent reduction.');
                                }

                                if ($form_values['reduction_max_amount'][$quantity_discount_rule_action]
                                    && !Validate::isPrice($form_values['reduction_max_amount'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('You must define a valid maximum reduction amount.');
                                }

                                if ($form_values['nb_repetitions'][$quantity_discount_rule_action] == 'custom'
                                    && !($form_values['nb_repetitions_custom'][$quantity_discount_rule_action] > 0)) {
                                    $this->errors[] = Tools::displayError('You must define the number of repetitions.');
                                }

                                if ($form_values['filter_by_product'][$quantity_discount_rule_action]
                                    && !array_filter($form_values['action_product_select'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('If you filter by product, you have to select at least 1 product');
                                }

                                if ($form_values['filter_by_attribute'][$quantity_discount_rule_action]
                                    && !array_filter($form_values['action_attribute_select'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('If you filter by attribute, you have to select at least 1 attribute');
                                }

                                if ($form_values['filter_by_feature'][$quantity_discount_rule_action]
                                    && !array_filter($form_values['action_feature_select'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('If you filter by feature, you have to select at least 1 feature');
                                }

                                if ($form_values['filter_by_category'][$quantity_discount_rule_action]
                                    && !array_filter($form_values['action_category_select'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('If you filter by category, you have to select at least 1 category');
                                }

                                if ($form_values['filter_by_supplier'][$quantity_discount_rule_action]
                                    && !array_filter($form_values['action_supplier_select'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('If you filter by supplier, you have to select at least 1 supplier');
                                }

                                if ($form_values['filter_by_manufacturer'][$quantity_discount_rule_action]
                                    && !array_filter($form_values['action_manufacturer_select'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('If you filter by manufacturer, you have to select at least 1 manufacturer');
                                }

                                break;

                            case 15:
                            case 17:
                                if (!$form_values['group_products_by'][$quantity_discount_rule_action]) {
                                    $this->errors[] = Tools::displayError('You must define how to group products.');
                                }

                                if (!Validate::isInt($form_values['products_nb_each'][$quantity_discount_rule_action])
                                    || !($form_values['products_nb_each'][$quantity_discount_rule_action] > 1)) {
                                    $this->errors[] = Tools::displayError('You must define the number of products to buy.');
                                }

                                if (!Validate::isPrice($form_values['reduction_amount'][$quantity_discount_rule_action])
                                    || !($form_values['reduction_amount'][$quantity_discount_rule_action] > 0)) {
                                    $this->errors[] = Tools::displayError('You must define a valid amount.');
                                }

                                if ($form_values['nb_repetitions'][$quantity_discount_rule_action] == 'custom'
                                    && !($form_values['nb_repetitions_custom'][$quantity_discount_rule_action] > 0)) {
                                    $this->errors[] = Tools::displayError('You must define the number of repetitions.');
                                }

                                if ($form_values['filter_by_product'][$quantity_discount_rule_action]
                                    && !array_filter($form_values['action_product_select'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('If you filter by product, you have to select at least 1 product');
                                }

                                if ($form_values['filter_by_attribute'][$quantity_discount_rule_action]
                                    && !array_filter($form_values['action_attribute_select'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('If you filter by attribute, you have to select at least 1 attribute');
                                }

                                if ($form_values['filter_by_feature'][$quantity_discount_rule_action]
                                    && !array_filter($form_values['action_feature_select'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('If you filter by feature, you have to select at least 1 feature');
                                }

                                if ($form_values['filter_by_category'][$quantity_discount_rule_action]
                                    && !array_filter($form_values['action_category_select'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('If you filter by category, you have to select at least 1 category');
                                }

                                if ($form_values['filter_by_supplier'][$quantity_discount_rule_action]
                                    && !array_filter($form_values['action_supplier_select'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('If you filter by supplier, you have to select at least 1 supplier');
                                }

                                if ($form_values['filter_by_manufacturer'][$quantity_discount_rule_action]
                                    && !array_filter($form_values['action_manufacturer_select'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('If you filter by manufacturer, you have to select at least 1 manufacturer');
                                }

                                break;

                            case 18:
                            case 20:
                                if (!$form_values['group_products_by'][$quantity_discount_rule_action]) {
                                    $this->errors[] = Tools::displayError('You must define how to group products.');
                                }

                                if (!(int)$form_values['products_nb_each'][$quantity_discount_rule_action]) {
                                    $this->errors[] = Tools::displayError('You must define every number of products.');
                                }

                                if (!Validate::isInt($form_values['apply_discount_to_nb'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('You must define from how many products apply discount.');
                                }

                                if (!($form_values['reduction_amount'][$quantity_discount_rule_action] > 0)
                                    || !Validate::isPrice($form_values['reduction_amount'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('You must define a valid reduction amount.');
                                }

                                if ($form_values['nb_repetitions'][$quantity_discount_rule_action] == 'custom'
                                    && !($form_values['nb_repetitions_custom'][$quantity_discount_rule_action] > 0)) {
                                    $this->errors[] = Tools::displayError('You must define the number of repetitions.');
                                }

                                if ($form_values['filter_by_product'][$quantity_discount_rule_action]
                                    && !array_filter($form_values['action_product_select'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('If you filter by product, you have to select at least 1 product');
                                }

                                if ($form_values['filter_by_attribute'][$quantity_discount_rule_action]
                                    && !array_filter($form_values['action_attribute_select'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('If you filter by attribute, you have to select at least 1 attribute');
                                }

                                if ($form_values['filter_by_feature'][$quantity_discount_rule_action]
                                    && !array_filter($form_values['action_feature_select'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('If you filter by feature, you have to select at least 1 feature');
                                }

                                if ($form_values['filter_by_category'][$quantity_discount_rule_action]
                                    && !array_filter($form_values['action_category_select'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('If you filter by category, you have to select at least 1 category');
                                }

                                if ($form_values['filter_by_supplier'][$quantity_discount_rule_action]
                                    && !array_filter($form_values['action_supplier_select'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('If you filter by supplier, you have to select at least 1 supplier');
                                }

                                if ($form_values['filter_by_manufacturer'][$quantity_discount_rule_action]
                                    && !array_filter($form_values['action_manufacturer_select'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('If you filter by manufacturer, you have to select at least 1 manufacturer');
                                }

                                break;

                            case 19:
                                if (!$form_values['group_products_by'][$quantity_discount_rule_action]) {
                                    $this->errors[] = Tools::displayError('You must define how to group products.');
                                }

                                if (!(int)$form_values['products_nb_each'][$quantity_discount_rule_action]) {
                                    $this->errors[] = Tools::displayError('You must define every number of products.');
                                }

                                if (!Validate::isInt($form_values['apply_discount_to_nb'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('You must define from how many products apply discount.');
                                }

                                if (!$form_values['reduction_percent'][$quantity_discount_rule_action] > 0
                                    || !Validate::isPercentage((float)str_replace(',', '.', $form_values['reduction_percent'][$quantity_discount_rule_action]))) {
                                    $this->errors[] = Tools::displayError('You must define a valid percent reduction.');
                                }

                                if ($form_values['reduction_max_amount'][$quantity_discount_rule_action]
                                    && !Validate::isPrice($form_values['reduction_max_amount'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('You must define a valid maximum reduction amount.');
                                }

                                if ($form_values['reduction_product_max_amount'][$quantity_discount_rule_action]
                                    && !Validate::isPrice($form_values['reduction_product_max_amount'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('You must define a valid maximum reduction amount.');
                                }

                                if ($form_values['nb_repetitions'][$quantity_discount_rule_action] == 'custom'
                                    && !($form_values['nb_repetitions_custom'][$quantity_discount_rule_action] > 0)) {
                                    $this->errors[] = Tools::displayError('You must define the number of repetitions.');
                                }

                                if ($form_values['filter_by_product'][$quantity_discount_rule_action]
                                    && !array_filter($form_values['action_product_select'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('If you filter by product, you have to select at least 1 product');
                                }

                                if ($form_values['filter_by_attribute'][$quantity_discount_rule_action]
                                    && !array_filter($form_values['action_attribute_select'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('If you filter by attribute, you have to select at least 1 attribute');
                                }

                                if ($form_values['filter_by_feature'][$quantity_discount_rule_action]
                                    && !array_filter($form_values['action_feature_select'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('If you filter by feature, you have to select at least 1 feature');
                                }

                                if ($form_values['filter_by_category'][$quantity_discount_rule_action]
                                    && !array_filter($form_values['action_category_select'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('If you filter by category, you have to select at least 1 category');
                                }

                                if ($form_values['filter_by_supplier'][$quantity_discount_rule_action]
                                    && !array_filter($form_values['action_supplier_select'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('If you filter by supplier, you have to select at least 1 supplier');
                                }

                                if ($form_values['filter_by_manufacturer'][$quantity_discount_rule_action]
                                    && !array_filter($form_values['action_manufacturer_select'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('If you filter by manufacturer, you have to select at least 1 manufacturer');
                                }

                                break;

                            case 21:
                                if (!($form_values['reduction_buy_amount'][$quantity_discount_rule_action] > 0)
                                    || !Validate::isPrice($form_values['reduction_buy_amount'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('You must define a valid amount.');
                                }

                                if (!($form_values['reduction_amount'][$quantity_discount_rule_action] > 0)
                                    || !Validate::isPrice($form_values['reduction_amount'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('You must define a valid reduction amount.');
                                }

                                if (($form_values['reduction_buy_amount'][$quantity_discount_rule_action] > 0)
                                    && Validate::isPrice($form_values['reduction_buy_amount'][$quantity_discount_rule_action])
                                    && ($form_values['reduction_amount'][$quantity_discount_rule_action] > 0)
                                    && Validate::isPrice($form_values['reduction_amount'][$quantity_discount_rule_action])
                                    && $form_values['reduction_buy_amount'][$quantity_discount_rule_action] > $form_values['reduction_amount'][$quantity_discount_rule_action]) {
                                    $this->errors[] = Tools::displayError('Reduction can\'t be higher than spent.');
                                }

                                break;

                            case 35:
                                if (!($form_values['reduction_amount'][$quantity_discount_rule_action] > 0)
                                    || !Validate::isPrice($form_values['reduction_amount'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('You must define a valid reduction amount.');
                                }

                                if ($form_values['reduction_buy_over'][$quantity_discount_rule_action]
                                    && !Validate::isPrice($form_values['reduction_buy_over'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('You must define a valid price.');
                                }

                                if (!$form_values['gift_product'][$quantity_discount_rule_action]) {
                                    $this->errors[] = Tools::displayError('You must select a product');
                                }

                                break;

                            case 26:
                                if (!$form_values['reduction_percent'][$quantity_discount_rule_action] > 0
                                    || !Validate::isPercentage((float)str_replace(',', '.', $form_values['reduction_percent'][$quantity_discount_rule_action]))) {
                                    $this->errors[] = Tools::displayError('You must define a valid percent reduction.');
                                }

                                if ($form_values['reduction_buy_over'][$quantity_discount_rule_action]
                                    && !Validate::isPrice($form_values['reduction_buy_over'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('You must define a valid price.');
                                }

                                if ($form_values['reduction_max_amount'][$quantity_discount_rule_action]
                                    && !Validate::isPrice($form_values['reduction_max_amount'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('You must define a valid maximum reduction amount.');
                                }

                                break;

                            case 27:
                            case 29:
                                if (!(int)$form_values['products_nb_each'][$quantity_discount_rule_action]) {
                                    $this->errors[] = Tools::displayError('You must define to how many products apply discount.');
                                }

                                if (!($form_values['reduction_amount'][$quantity_discount_rule_action] > 0)
                                    || !Validate::isPrice($form_values['reduction_amount'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('You must define a valid amount.');
                                }

                                if ($form_values['filter_by_product'][$quantity_discount_rule_action]
                                    && !array_filter($form_values['action_product_select'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('If you filter by product, you have to select at least 1 product');
                                }

                                if ($form_values['filter_by_attribute'][$quantity_discount_rule_action]
                                    && !array_filter($form_values['action_attribute_select'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('If you filter by attribute, you have to select at least 1 attribute');
                                }

                                if ($form_values['filter_by_feature'][$quantity_discount_rule_action]
                                    && !array_filter($form_values['action_feature_select'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('If you filter by feature, you have to select at least 1 feature');
                                }

                                if ($form_values['filter_by_category'][$quantity_discount_rule_action]
                                    && !array_filter($form_values['action_category_select'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('If you filter by category, you have to select at least 1 category');
                                }

                                if ($form_values['filter_by_supplier'][$quantity_discount_rule_action]
                                    && !array_filter($form_values['action_supplier_select'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('If you filter by supplier, you have to select at least 1 supplier');
                                }

                                if ($form_values['filter_by_manufacturer'][$quantity_discount_rule_action]
                                    && !array_filter($form_values['action_manufacturer_select'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('If you filter by manufacturer, you have to select at least 1 manufacturer');
                                }

                                break;

                            case 28:
                                if (!(int)$form_values['products_nb_each'][$quantity_discount_rule_action]) {
                                    $this->errors[] = Tools::displayError('You must define to how many products apply discount.');
                                }

                                if (!$form_values['reduction_percent'][$quantity_discount_rule_action] > 0
                                    || !Validate::isPercentage((float)str_replace(',', '.', $form_values['reduction_percent'][$quantity_discount_rule_action]))) {
                                    $this->errors[] = Tools::displayError('You must define a valid percent reduction.');
                                }

                                if ($form_values['reduction_max_amount'][$quantity_discount_rule_action]
                                    && !Validate::isPrice($form_values['reduction_max_amount'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('You must define a valid maximum reduction amount.');
                                }

                                if ($form_values['reduction_product_max_amount'][$quantity_discount_rule_action]
                                    && !Validate::isPrice($form_values['reduction_product_max_amount'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('You must define a valid maximum reduction amount.');
                                }

                                if ($form_values['nb_repetitions'][$quantity_discount_rule_action] == 'custom'
                                    && !($form_values['nb_repetitions_custom'][$quantity_discount_rule_action] > 0)) {
                                    $this->errors[] = Tools::displayError('You must define the number of repetitions.');
                                }

                                if ($form_values['filter_by_product'][$quantity_discount_rule_action]
                                    && !array_filter($form_values['action_product_select'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('If you filter by product, you have to select at least 1 product');
                                }

                                if ($form_values['filter_by_attribute'][$quantity_discount_rule_action]
                                    && !array_filter($form_values['action_attribute_select'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('If you filter by attribute, you have to select at least 1 attribute');
                                }

                                if ($form_values['filter_by_feature'][$quantity_discount_rule_action]
                                    && !array_filter($form_values['action_feature_select'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('If you filter by feature, you have to select at least 1 feature');
                                }

                                if ($form_values['filter_by_category'][$quantity_discount_rule_action]
                                    && !array_filter($form_values['action_category_select'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('If you filter by category, you have to select at least 1 category');
                                }

                                if ($form_values['filter_by_supplier'][$quantity_discount_rule_action]
                                    && !array_filter($form_values['action_supplier_select'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('If you filter by supplier, you have to select at least 1 supplier');
                                }

                                if ($form_values['filter_by_manufacturer'][$quantity_discount_rule_action]
                                    && !array_filter($form_values['action_manufacturer_select'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('If you filter by manufacturer, you have to select at least 1 manufacturer');
                                }

                                break;

                            case 30:
                                if (!(int)$form_values['apply_discount_to_nb'][$quantity_discount_rule_action]) {
                                    $this->errors[] = Tools::displayError('You must define the number of units to gift.');
                                }

                                if (!$form_values['gift_product'][$quantity_discount_rule_action]) {
                                    $this->errors[] = Tools::displayError('You must select a product');
                                }

                                break;

                            /**
                             *
                             * Buy more than X units and get discount in all units - Fixed discount
                             *
                             */
                            case 32:
                                if (!$form_values['group_products_by'][$quantity_discount_rule_action]) {
                                    $this->errors[] = Tools::displayError('You must define how to group products.');
                                }

                                if (!(int)$form_values['products_nb_each'][$quantity_discount_rule_action]) {
                                    $this->errors[] = Tools::displayError('You must define the number of products to buy.');
                                }

                                if (!Validate::isPrice($form_values['reduction_amount'][$quantity_discount_rule_action])
                                    || !($form_values['reduction_amount'][$quantity_discount_rule_action] > 0)) {
                                    $this->errors[] = Tools::displayError('You must define a valid reduction amount.');
                                }

                                if ($form_values['filter_by_product'][$quantity_discount_rule_action]
                                    && !array_filter($form_values['action_product_select'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('If you filter by product, you have to select at least 1 product');
                                }

                                if ($form_values['filter_by_attribute'][$quantity_discount_rule_action]
                                    && !array_filter($form_values['action_attribute_select'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('If you filter by attribute, you have to select at least 1 attribute');
                                }

                                if ($form_values['filter_by_feature'][$quantity_discount_rule_action]
                                    && !array_filter($form_values['action_feature_select'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('If you filter by feature, you have to select at least 1 feature');
                                }

                                if ($form_values['filter_by_category'][$quantity_discount_rule_action]
                                    && !array_filter($form_values['action_category_select'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('If you filter by category, you have to select at least 1 category');
                                }

                                if ($form_values['filter_by_supplier'][$quantity_discount_rule_action]
                                    && !array_filter($form_values['action_supplier_select'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('If you filter by supplier, you have to select at least 1 supplier');
                                }

                                if ($form_values['filter_by_manufacturer'][$quantity_discount_rule_action]
                                    && !array_filter($form_values['action_manufacturer_select'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('If you filter by manufacturer, you have to select at least 1 manufacturer');
                                }

                                break;

                            /**
                             *
                             * Buy more than X units and get discount in all units - Percentage discount
                             *
                             */
                            case 33:
                                if (!$form_values['group_products_by'][$quantity_discount_rule_action]) {
                                    $this->errors[] = Tools::displayError('You must define how to group products.');
                                }

                                if (!(int)$form_values['products_nb_each'][$quantity_discount_rule_action]) {
                                    $this->errors[] = Tools::displayError('You must define the number of products to buy.');
                                }

                                if (!$form_values['reduction_percent'][$quantity_discount_rule_action] > 0
                                    || !Validate::isPercentage((float)str_replace(',', '.', $form_values['reduction_percent'][$quantity_discount_rule_action]))) {
                                    $this->errors[] = Tools::displayError('You must define a valid percent reduction.');
                                }

                                if ($form_values['reduction_max_amount'][$quantity_discount_rule_action]
                                    && !Validate::isPrice($form_values['reduction_max_amount'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('You must define a valid maximum reduction amount.');
                                }

                                if ($form_values['reduction_product_max_amount'][$quantity_discount_rule_action]
                                    && !Validate::isPrice($form_values['reduction_product_max_amount'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('You must define a valid maximum reduction amount.');
                                }

                                if ($form_values['filter_by_product'][$quantity_discount_rule_action]
                                    && !array_filter($form_values['action_product_select'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('If you filter by product, you have to select at least 1 product');
                                }

                                if ($form_values['filter_by_attribute'][$quantity_discount_rule_action]
                                    && !array_filter($form_values['action_attribute_select'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('If you filter by attribute, you have to select at least 1 attribute');
                                }

                                if ($form_values['filter_by_feature'][$quantity_discount_rule_action]
                                    && !array_filter($form_values['action_feature_select'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('If you filter by feature, you have to select at least 1 feature');
                                }

                                if ($form_values['filter_by_category'][$quantity_discount_rule_action]
                                    && !array_filter($form_values['action_category_select'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('If you filter by category, you have to select at least 1 category');
                                }

                                if ($form_values['filter_by_supplier'][$quantity_discount_rule_action]
                                    && !array_filter($form_values['action_supplier_select'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('If you filter by supplier, you have to select at least 1 supplier');
                                }

                                if ($form_values['filter_by_manufacturer'][$quantity_discount_rule_action]
                                    && !array_filter($form_values['action_manufacturer_select'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('If you filter by manufacturer, you have to select at least 1 manufacturer');
                                }

                                break;

                            /**
                             *
                             * Buy more than X units and get discount in all units - Fixed price
                             *
                             */
                            case 34:
                                if (!$form_values['group_products_by'][$quantity_discount_rule_action]) {
                                    $this->errors[] = Tools::displayError('You must define how to group products.');
                                }

                                if (!(int)$form_values['products_nb_each'][$quantity_discount_rule_action]) {
                                    $this->errors[] = Tools::displayError('You must define the number of products to buy.');
                                }

                                if (!($form_values['reduction_amount'][$quantity_discount_rule_action] > 0)
                                    || !Validate::isPrice($form_values['reduction_amount'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('You must define a valid fixed amount.');
                                }

                                if ($form_values['filter_by_product'][$quantity_discount_rule_action]
                                    && !array_filter($form_values['action_product_select'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('If you filter by product, you have to select at least 1 product');
                                }

                                if ($form_values['filter_by_attribute'][$quantity_discount_rule_action]
                                    && !array_filter($form_values['action_attribute_select'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('If you filter by attribute, you have to select at least 1 attribute');
                                }

                                if ($form_values['filter_by_feature'][$quantity_discount_rule_action]
                                    && !array_filter($form_values['action_feature_select'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('If you filter by feature, you have to select at least 1 feature');
                                }

                                if ($form_values['filter_by_category'][$quantity_discount_rule_action]
                                    && !array_filter($form_values['action_category_select'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('If you filter by category, you have to select at least 1 category');
                                }

                                if ($form_values['filter_by_supplier'][$quantity_discount_rule_action]
                                    && !array_filter($form_values['action_supplier_select'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('If you filter by supplier, you have to select at least 1 supplier');
                                }

                                if ($form_values['filter_by_manufacturer'][$quantity_discount_rule_action]
                                    && !array_filter($form_values['action_manufacturer_select'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('If you filter by manufacturer, you have to select at least 1 manufacturer');
                                }

                                break;

                            /**
                             *
                             * Buy X
                             *
                             */
                            case 22:
                            case 23:
                                $actionBuyX = true;
                                if (!$form_values['group_products_by'][$quantity_discount_rule_action]) {
                                    $this->errors[] = Tools::displayError('You must define how to group products.');
                                }

                                if (!(int)$form_values['products_nb_each'][$quantity_discount_rule_action]) {
                                    $this->errors[] = Tools::displayError('You must define the number of products to buy.');
                                }

                                if ($form_values['filter_by_product'][$quantity_discount_rule_action]
                                    && !array_filter($form_values['action_product_select'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('If you filter by product, you have to select at least 1 product');
                                }

                                if ($form_values['filter_by_attribute'][$quantity_discount_rule_action]
                                    && !array_filter($form_values['action_attribute_select'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('If you filter by attribute, you have to select at least 1 attribute');
                                }

                                if ($form_values['filter_by_feature'][$quantity_discount_rule_action]
                                    && !array_filter($form_values['action_feature_select'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('If you filter by feature, you have to select at least 1 feature');
                                }

                                if ($form_values['filter_by_category'][$quantity_discount_rule_action]
                                    && !array_filter($form_values['action_category_select'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('If you filter by category, you have to select at least 1 category');
                                }

                                if ($form_values['filter_by_supplier'][$quantity_discount_rule_action]
                                    && !array_filter($form_values['action_supplier_select'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('If you filter by supplier, you have to select at least 1 supplier');
                                }

                                if ($form_values['filter_by_manufacturer'][$quantity_discount_rule_action]
                                    && !array_filter($form_values['action_manufacturer_select'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('If you filter by manufacturer, you have to select at least 1 manufacturer');
                                }
                                break;

                            case 36:
                                if (!(int)$form_values['apply_discount_to_nb'][$quantity_discount_rule_action]) {
                                    $this->errors[] = Tools::displayError('You must define the number of units to gift.');
                                }

                                if (!($form_values['reduction_amount'][$quantity_discount_rule_action] > 0)
                                    || !Validate::isPrice($form_values['reduction_amount'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('You must define a valid fixed amount.');
                                }

                                if (!$form_values['gift_product'][$quantity_discount_rule_action]) {
                                    $this->errors[] = Tools::displayError('You must select a product');
                                }

                                break;

                            /**
                             *
                             * Get a discount on A - Fixed discount
                             *
                             */
                            case 100:
                                $actionGetY = true;
                                if (!$form_values['group_products_by'][$quantity_discount_rule_action]) {
                                    $this->errors[] = Tools::displayError('You must define how to group products.');
                                }

                                if (!(int)$form_values['apply_discount_to_nb'][$quantity_discount_rule_action]) {
                                    $this->errors[] = Tools::displayError('You must define from how many products apply discount.');
                                }

                                if (!($form_values['reduction_amount'][$quantity_discount_rule_action] > 0)
                                    || !Validate::isPrice($form_values['reduction_amount'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('You must define a valid reduction amount.');
                                }

                                if ($form_values['filter_by_product'][$quantity_discount_rule_action]
                                    && !array_filter($form_values['action_product_select'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('If you filter by product, you have to select at least 1 product');
                                }

                                if ($form_values['filter_by_attribute'][$quantity_discount_rule_action]
                                    && !array_filter($form_values['action_attribute_select'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('If you filter by attribute, you have to select at least 1 attribute');
                                }

                                if ($form_values['filter_by_feature'][$quantity_discount_rule_action]
                                    && !array_filter($form_values['action_feature_select'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('If you filter by feature, you have to select at least 1 feature');
                                }

                                if ($form_values['filter_by_category'][$quantity_discount_rule_action]
                                    && !array_filter($form_values['action_category_select'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('If you filter by category, you have to select at least 1 category');
                                }

                                if ($form_values['filter_by_supplier'][$quantity_discount_rule_action]
                                    && !array_filter($form_values['action_supplier_select'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('If you filter by supplier, you have to select at least 1 supplier');
                                }

                                if ($form_values['filter_by_manufacturer'][$quantity_discount_rule_action]
                                    && !array_filter($form_values['action_manufacturer_select'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('If you filter by manufacturer, you have to select at least 1 manufacturer');
                                }
                                break;

                            /**
                             *
                             * Get a discount on A - Percentage discount
                             *
                             */
                            case 101:
                                $actionGetY = true;
                                if (!$form_values['group_products_by'][$quantity_discount_rule_action]) {
                                    $this->errors[] = Tools::displayError('You must define how to group products.');
                                }

                                if (!(int)$form_values['apply_discount_to_nb'][$quantity_discount_rule_action]) {
                                    $this->errors[] = Tools::displayError('You must define from how many products apply discount.');
                                }

                                if ($form_values['reduction_max_amount'][$quantity_discount_rule_action]
                                    && !Validate::isPrice($form_values['reduction_max_amount'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('You must define a valid maximum reduction amount.');
                                }

                                if ($form_values['reduction_product_max_amount'][$quantity_discount_rule_action]
                                    && !Validate::isPrice($form_values['reduction_product_max_amount'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('You must define a valid maximum reduction amount.');
                                }

                                if ($form_values['filter_by_product'][$quantity_discount_rule_action]
                                    && !array_filter($form_values['action_product_select'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('If you filter by product, you have to select at least 1 product');
                                }

                                if ($form_values['filter_by_attribute'][$quantity_discount_rule_action]
                                    && !array_filter($form_values['action_attribute_select'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('If you filter by attribute, you have to select at least 1 attribute');
                                }

                                if ($form_values['filter_by_feature'][$quantity_discount_rule_action]
                                    && !array_filter($form_values['action_feature_select'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('If you filter by feature, you have to select at least 1 feature');
                                }

                                if ($form_values['filter_by_category'][$quantity_discount_rule_action]
                                    && !array_filter($form_values['action_category_select'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('If you filter by category, you have to select at least 1 category');
                                }

                                if ($form_values['filter_by_supplier'][$quantity_discount_rule_action]
                                    && !array_filter($form_values['action_supplier_select'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('If you filter by supplier, you have to select at least 1 supplier');
                                }

                                if ($form_values['filter_by_manufacturer'][$quantity_discount_rule_action]
                                    && !array_filter($form_values['action_manufacturer_select'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('If you filter by manufacturer, you have to select at least 1 manufacturer');
                                }
                                break;

                            /**
                             *
                             * Get a discount on A - Fixed price
                             *
                             */
                            case 102:
                                $actionGetY = true;
                                if (!$form_values['group_products_by'][$quantity_discount_rule_action]) {
                                    $this->errors[] = Tools::displayError('You must define how to group products.');
                                }

                                if (!(int)$form_values['apply_discount_to_nb'][$quantity_discount_rule_action]) {
                                    $this->errors[] = Tools::displayError('You must define from how many products apply discount.');
                                }

                                if ($form_values['filter_by_product'][$quantity_discount_rule_action]
                                    && !array_filter($form_values['action_product_select'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('If you filter by product, you have to select at least 1 product');
                                }

                                if ($form_values['filter_by_attribute'][$quantity_discount_rule_action]
                                    && !array_filter($form_values['action_attribute_select'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('If you filter by attribute, you have to select at least 1 attribute');
                                }

                                if ($form_values['filter_by_feature'][$quantity_discount_rule_action]
                                    && !array_filter($form_values['action_feature_select'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('If you filter by feature, you have to select at least 1 feature');
                                }

                                if ($form_values['filter_by_category'][$quantity_discount_rule_action]
                                    && !array_filter($form_values['action_category_select'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('If you filter by category, you have to select at least 1 category');
                                }

                                if ($form_values['filter_by_supplier'][$quantity_discount_rule_action]
                                    && !array_filter($form_values['action_supplier_select'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('If you filter by supplier, you have to select at least 1 supplier');
                                }

                                if ($form_values['filter_by_manufacturer'][$quantity_discount_rule_action]
                                    && !array_filter($form_values['action_manufacturer_select'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('If you filter by manufacturer, you have to select at least 1 manufacturer');
                                }
                                break;

                            /**
                             *
                             * Gift a product
                             *
                             */
                            case 103:
                                $actionGetY = true;
                                if (!(int)$form_values['apply_discount_to_nb'][$quantity_discount_rule_action]) {
                                    $this->errors[] = Tools::displayError('You must define the number of units to gift.');
                                }

                                if (!$form_values['gift_product'][$quantity_discount_rule_action]) {
                                    $this->errors[] = Tools::displayError('You must select a product');
                                }

                                break;

                            case 107:
                                $actionGetY = true;
                                if (!(int)$form_values['apply_discount_to_nb'][$quantity_discount_rule_action]) {
                                    $this->errors[] = Tools::displayError('You must define the number of units to gift.');
                                }

                                /*if (!($form_values['reduction_amount'][$quantity_discount_rule_action] > 0)
                                    || !Validate::isPrice($form_values['reduction_amount'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('You must define a valid fixed amount.');
                                }*/

                                if (!$form_values['gift_product'][$quantity_discount_rule_action]) {
                                    $this->errors[] = Tools::displayError('You must select a product');
                                }

                                break;

                            /**
                             *
                             * Get a fixed discount
                             *
                             */
                            case 104:
                                $actionGetY = true;
                                if (!($form_values['reduction_amount'][$quantity_discount_rule_action] > 0)
                                    || !Validate::isPrice($form_values['reduction_amount'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('You must define a valid reduction amount.');
                                }

                                break;

                            /**
                             *
                             * Get a percentage discount
                             *
                             */
                            case 105:
                                $actionGetY = true;
                                if ($form_values['reduction_max_amount'][$quantity_discount_rule_action]
                                    && !Validate::isPrice($form_values['reduction_max_amount'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('You must define a valid maximum reduction amount.');
                                }

                                if ($form_values['reduction_product_max_amount'][$quantity_discount_rule_action]
                                    && !Validate::isPrice($form_values['reduction_product_max_amount'][$quantity_discount_rule_action])) {
                                    $this->errors[] = Tools::displayError('You must define a valid maximum reduction amount.');
                                }

                                break;

                            /**
                             *
                             * Get a fixed price
                             *
                             */
                            case 106:
                                $actionGetY = true;
                                if (!(int)$form_values['apply_discount_to_nb'][$quantity_discount_rule_action]) {
                                    $this->errors[] = Tools::displayError('You must define from how many products apply discount.');
                                }

                                break;
                        }
                    }
                }
            }

            if (!$actions) {
                $this->errors[] = Tools::displayError('You must define at least one action.');
            }

            if (($actionBuyX && !$actionGetY) || (!$actionBuyX && $actionGetY)) {
                $this->errors[] = Tools::displayError('You must define a product to buy and a product to discount.');
            }

            //Validate messages
            $form_values = array();
            foreach ($this->message_vars as $message_var) {
                $form_values[$message_var] = Tools::getValue('message_'.$message_var);
            }

            if (($form_values['hook_name'] && array_filter($form_values['hook_name'])) || ($form_values['message'] && array_filter(array_map('array_filter', $form_values['message'])))) {
                foreach (array_keys($form_values['hook_name']) as $quantity_discount_rule_message) {
                    if ($form_values['hook_name'][$quantity_discount_rule_message] && !array_filter($form_values['message'][$quantity_discount_rule_message])) {
                        $this->errors[] = Tools::displayError('You must define a message.');
                    }

                    if (!$form_values['hook_name'][$quantity_discount_rule_message] && array_filter($form_values['message'][$quantity_discount_rule_message])) {
                        $this->errors[] = Tools::displayError('You must define a hook.');
                    }
                }
            }

            //Format code
            $_POST['code'] = Tools::strtoupper(trim(Tools::getValue('code')));
            $_POST['code_prefix'] = Tools::strtoupper(trim(Tools::getValue('code_prefix')));
        }

        return parent::postProcess();
    }

    protected function afterUpdate($current_object)
    {
        // All the associations are deleted for an update, then recreated when we call the "afterAdd" method
        $id_quantity_discount_rule = Tools::getValue('id_quantity_discount_rule');
        Db::getInstance()->delete('quantity_discount_rule_condition', '`id_quantity_discount_rule` = '.(int)$id_quantity_discount_rule);
        Db::getInstance()->delete('quantity_discount_rule_group', '`id_quantity_discount_rule` = '.(int)$id_quantity_discount_rule);
        Db::getInstance()->delete('quantity_discount_rule_action', '`id_quantity_discount_rule` = '.(int)$id_quantity_discount_rule);
        foreach ($this->condition_selectors as $type) {
            Db::getInstance()->delete('quantity_discount_rule_condition_'.$type, '`id_quantity_discount_rule` = '.(int)$id_quantity_discount_rule);
        }
        foreach ($this->action_selectors as $type) {
            Db::getInstance()->delete('quantity_discount_rule_action_'.$type, '`id_quantity_discount_rule` = '.(int)$id_quantity_discount_rule);
        }

        Db::getInstance()->delete('quantity_discount_rule_message_lang', '`id_quantity_discount_rule_message` IN (SELECT `id_quantity_discount_rule_message` FROM `'._DB_PREFIX_.'quantity_discount_rule_message` WHERE `id_quantity_discount_rule` = '.(int)$id_quantity_discount_rule.')');

        Db::getInstance()->delete('quantity_discount_rule_message', '`id_quantity_discount_rule` = '.(int)$id_quantity_discount_rule);

        $this->afterAdd($current_object);
    }

    protected function afterAdd($currentObject)
    {
        //Foreach condition group
        $form_values = array();
        $definition = ObjectModel::getDefinition('quantityDiscountRuleCondition');
        foreach (array_keys($definition['fields']) as $condition_var) {
            $form_values[$condition_var] = Tools::getValue('condition_'.$condition_var);
        }

        foreach ($this->condition_selectors as $condition_var) {
            $form_values['condition_'.$condition_var.'_select'] = Tools::getValue('condition_'.$condition_var.'_select_json');
        }

        $values = array();
        if ($form_values['id_type'] && array_filter($form_values['id_type'])) {
            foreach (array_keys($form_values['id_type']) as $quantity_discount_rule_group) {
                if (reset($form_values['id_type'][$quantity_discount_rule_group])) {
                    $quantityDiscountRuleGroup = new QuantityDiscountRuleGroup();
                    $quantityDiscountRuleGroup->id_quantity_discount_rule = (int)$currentObject->id;

                    if ($quantityDiscountRuleGroup->save()) {
                        //Foreach condition of a condition group
                        foreach (array_keys($form_values['id_type'][$quantity_discount_rule_group]) as $quantity_discount_rule_group_rule) {
                            $quantityDiscountRuleCondition = new QuantityDiscountRuleCondition();

                            foreach (array_keys($definition['fields']) as $field) {
                                if (array_key_exists($field, $form_values)) {
                                    $quantityDiscountRuleCondition->$field = $form_values[$field][$quantity_discount_rule_group][$quantity_discount_rule_group_rule];
                                }
                            }

                            $quantityDiscountRuleCondition->id_quantity_discount_rule = (int)$currentObject->id;
                            $quantityDiscountRuleCondition->id_quantity_discount_rule_group = $quantityDiscountRuleGroup->id;

                            if ($quantityDiscountRuleCondition->save()) {
                                // Add restrictions for generic entities like country, carrier and group
                                foreach ($this->condition_selectors as $type) {
                                    if (($array = $form_values['condition_'.$type.'_select'][$quantity_discount_rule_group][$quantity_discount_rule_group_rule])
                                        && (!isset($form_values['filter_by_'.$type][$quantity_discount_rule_group][$quantity_discount_rule_group_rule])
                                            || (isset($form_values['filter_by_'.$type][$quantity_discount_rule_group][$quantity_discount_rule_group_rule])
                                                && $form_values['filter_by_'.$type][$quantity_discount_rule_group][$quantity_discount_rule_group_rule]))) {
                                        if ($array[0] != "") {
                                            $values = array();
                                            foreach (Tools::jsonDecode($array[0]) as $id) {
                                                $values[] = '('.(int)$quantityDiscountRuleCondition->id.','.(int)$currentObject->id.','.(int)$id.')';
                                            }

                                            Db::getInstance()->execute('INSERT INTO `'._DB_PREFIX_.'quantity_discount_rule_condition_'.$type.'` (`id_quantity_discount_rule_condition`, `id_quantity_discount_rule`, `id_'.$type.'`) VALUES '.implode(',', $values));
                                        }
                                    }
                                }
                            } else {
                                $this->errors[] = Tools::displayError('Unexpected error when saving condition.');
                            }
                        }
                    } else {
                        $this->errors[] = Tools::displayError('Unexpected error when saving group.');
                    }
                }
            }
        }

        //Populate actions
        $form_values = array();
        $definition = ObjectModel::getDefinition('quantityDiscountRuleAction');
        foreach (array_keys($definition['fields']) as $action_var) {
            $form_values[$action_var] = Tools::getValue('action_'.$action_var);
        }

        foreach ($this->action_selectors as $action_var) {
            $form_values['action_'.$action_var.'_select'] = Tools::getValue('action_'.$action_var.'_select_json');
        }

        $values = array();
        if ($form_values['id_type'] && array_filter($form_values['id_type'])) {
            foreach (array_keys($form_values['id_type']) as $quantity_discount_rule_action) {
                $quantityDiscountRuleAction = new QuantityDiscountRuleAction();

                foreach (array_keys($definition['fields']) as $field) {
                    if (array_key_exists($field, $form_values)) {
                        $quantityDiscountRuleAction->$field = $form_values[$field][$quantity_discount_rule_action];
                    }
                }

                $quantityDiscountRuleAction->id_quantity_discount_rule = (int)$currentObject->id;

                if ($quantityDiscountRuleAction->save()) {
                    // Add restrictions for generic entities like country, carrier and group
                    foreach ($this->action_selectors as $type) {
                        if ($array = $form_values['action_'.$type.'_select'][$quantity_discount_rule_action]) {
                            if ($array[0] != "") {
                                $values = array();
                                foreach (Tools::jsonDecode($array[0]) as $id) {
                                    $values[] = '('.(int)$quantityDiscountRuleAction->id.','.(int)$currentObject->id.','.(int)$id.')';
                                }
                                $sql = 'INSERT INTO `'._DB_PREFIX_.'quantity_discount_rule_action_'.$type.'` (`id_quantity_discount_rule_action`, `id_quantity_discount_rule`, `id_'.$type.'`) VALUES '.implode(',', $values);
                                Db::getInstance()->execute($sql);
                            }
                        }
                    }
                } else {
                    $this->errors[] = Tools::displayError('Unexpected error when saving action.');
                }
            }
        }

        //Populate message
        $form_values = array();
        foreach ($this->message_vars as $message_vars) {
            $form_values[$message_vars] = Tools::getValue('message_'.$message_vars);
        }

        if (($form_values['hook_name'] && array_filter($form_values['hook_name'])) || ($form_values['message'] && array_filter(array_map('array_filter', $form_values['message'])))) {
            foreach (array_keys($form_values['hook_name']) as $quantity_discount_rule_message) {
                $quantityDiscountRuleMessage = new QuantityDiscountRuleMessage();

                $definition = ObjectModel::getDefinition('quantityDiscountRuleMessage');
                foreach (array_keys($definition['fields']) as $field) {
                    if (array_key_exists($field, $form_values) && $form_values[$field][$quantity_discount_rule_message]) {
                        $quantityDiscountRuleMessage->$field = $form_values[$field][$quantity_discount_rule_message];
                    } elseif (array_key_exists($field, $form_values) && !$form_values[$field][$quantity_discount_rule_message]) {
                        continue 2;
                    }
                }

                $quantityDiscountRuleMessage->id_quantity_discount_rule = (int)$currentObject->id;

                if (!$quantityDiscountRuleMessage->save()) {
                    $this->errors[] = Tools::displayError('Unexpected error when saving message.');
                }
            }
        }

        Cache::clean('QuantityDiscountRule::getQuantityDiscountRules*');

        if ((bool)Configuration::get('PS_CART_RULE_FEATURE_ACTIVE') != (bool)QuantityDiscountRule::isCurrentlyUsed(null, true)
            || (bool)QuantityDiscountRule::isCurrentlyUsed(null, true)) {
            Configuration::updateGlobalValue('PS_CART_RULE_FEATURE_ACTIVE', QuantityDiscountRule::isCurrentlyUsed(null, true));
        }

        if (!$currentObject->active) {
            QuantityDiscountRule::removeUnusedRules((int)$currentObject->id);
        }
    }

    public function processDelete()
    {
        if (Validate::isLoadedObject($object = $this->loadObject())) {
            QuantityDiscountRule::removeUnusedRules((int)$object->id);
            parent::processDelete();
        }

        return $object;
    }

    public function displayDuplicateLink($token = null, $id = null)
    {
        if (version_compare(_PS_VERSION_, '1.6', '<')) {
            $tpl = $this->createTemplate('list_action_duplicate_15.tpl');

            $tpl->assign(array(
                'href' => self::$currentIndex.'&'.$this->identifier.'='.$id.'&duplicate'.$this->table.'&token='.($token != null ? $token : $this->token),
                'action' => $this->l('Duplicate'),
            ));
        } else {
            $tpl = $this->createTemplate('list_action_duplicate.tpl');
            if (!array_key_exists('Bad SQL query', self::$cache_lang)) {
                self::$cache_lang['Duplicate'] = $this->l('Duplicate', 'Helper');
            }

            $duplicate = self::$currentIndex.'&'.$this->identifier.'='.$id.'&duplicate'.$this->table;

            $tpl->assign(array(
                'href' => self::$currentIndex.'&'.$this->identifier.'='.$id.'&duplicate'.$this->table.'&token='.($token != null ? $token : $this->token),
                'action' => self::$cache_lang['Duplicate'],
                'location' => $duplicate.'&token='.($token != null ? $token : $this->token),
            ));
        }

        return $tpl->fetch();
    }

    public function processDuplicate()
    {
        if (Validate::isLoadedObject($quantityDiscountRule = new QuantityDiscountRule((int)Tools::getValue('id_quantity_discount_rule')))) {
            $old_id = $quantityDiscountRule->id;
            unset($quantityDiscountRule->id);
            $quantityDiscountRule->active = 0;

            if ($quantityDiscountRule->add()
                && $this->duplicateTableRecords($quantityDiscountRule->id, $old_id)) {
                $this->redirect_after = self::$currentIndex.(Tools::getIsset('id_quantity_discount_rule') ? '&id_quantity_discount_rule='.(int)Tools::getValue('id_quantity_discount_rule') : '').'&conf=19&token='.$this->token;
            } else {
                $this->errors[] = Tools::displayError('An error occurred while creating an object.');
            }
        }
    }

    public function duplicateTableRecords($new_id, $old_id)
    {
        $tables = array(
            'quantity_discount_rule_action',
            'quantity_discount_rule_condition',
            'quantity_discount_rule_message',
        );

        $groupRelation = array();

        foreach ($tables as $table) {
            if ($table != 'quantity_discount_rule_message_lang') {
                $result = Db::getInstance()->executeS(
                    'SELECT *
                    FROM `'._DB_PREFIX_.$table.'`
                    WHERE `id_quantity_discount_rule` = '.(int)$old_id
                );
            }

            foreach ($result as $row) {
                $removedField = array_splice($row, 0, 1);

                $row['id_quantity_discount_rule'] = $new_id;

                Db::getInstance()->execute(
                    'INSERT INTO `'._DB_PREFIX_.$table.'` (`'.implode('`, `', array_keys($row)).'`)
                    VALUES (\''.implode('\', \'', $row).'\')'
                );

                $insertedId = Db::getInstance()->Insert_ID();

                if ($table == 'quantity_discount_rule_condition') {
                    // Conditions
                    $subtables = array(
                        'quantity_discount_rule_condition_attribute',
                        'quantity_discount_rule_condition_carrier',
                        'quantity_discount_rule_condition_category',
                        'quantity_discount_rule_condition_country',
                        'quantity_discount_rule_condition_currency',
                        'quantity_discount_rule_condition_gender',
                        'quantity_discount_rule_condition_group',
                        'quantity_discount_rule_condition_manufacturer',
                        'quantity_discount_rule_condition_order_state',
                        'quantity_discount_rule_condition_product',
                        'quantity_discount_rule_condition_shop',
                        'quantity_discount_rule_condition_state',
                        'quantity_discount_rule_condition_supplier',
                        'quantity_discount_rule_condition_zone',
                    );

                    foreach ($subtables as $subtable) {
                        $result2 = Db::getInstance()->executeS(
                            'SELECT *
                            FROM `'._DB_PREFIX_.$subtable.'`
                            WHERE `id_quantity_discount_rule_condition` = '.(int)$removedField['id_quantity_discount_rule_condition']
                        );

                        foreach ($result2 as $row2) {
                            $keys = array_keys($row2);

                            Db::getInstance()->execute(
                                'INSERT INTO `'._DB_PREFIX_.$subtable.'`
                                VALUES ('.(int)$insertedId.', '.(int)$row['id_quantity_discount_rule'].', '.(int)$row2[$keys[2]].')'
                            );
                        }
                    }

                    if (!isset($groupRelation[$row['id_quantity_discount_rule_group']])) {
                        $maxGroup = Db::getInstance()->getValue(
                            'SELECT MAX(id_quantity_discount_rule_group) + 1
                            FROM `'._DB_PREFIX_.'quantity_discount_rule_group`'
                        );

                        $groupRelation[$row['id_quantity_discount_rule_group']] = $maxGroup;

                        Db::getInstance()->execute(
                            'INSERT INTO `'._DB_PREFIX_.'quantity_discount_rule_group`
                            VALUES ('.(int)$groupRelation[$row['id_quantity_discount_rule_group']].', '.(int)$row['id_quantity_discount_rule'].')'
                        );
                    }

                    Db::getInstance()->execute(
                        'UPDATE `'._DB_PREFIX_.'quantity_discount_rule_condition`
                        SET `id_quantity_discount_rule_group`= '.(int)$groupRelation[$row['id_quantity_discount_rule_group']].'
                        WHERE `id_quantity_discount_rule_condition` = '.(int)$insertedId
                    );
                } elseif ($table == 'quantity_discount_rule_action') {
                    // Actions
                    $subtables = array(
                        'quantity_discount_rule_action_attribute',
                        'quantity_discount_rule_action_category',
                        'quantity_discount_rule_action_manufacturer',
                        'quantity_discount_rule_action_product',
                        'quantity_discount_rule_action_supplier',
                    );

                    foreach ($subtables as $subtable) {
                        $result2 = Db::getInstance()->executeS(
                            'SELECT *
                            FROM `'._DB_PREFIX_.$subtable.'`
                            WHERE `id_quantity_discount_rule_action` = '.(int)$removedField['id_quantity_discount_rule_action']
                        );

                        foreach ($result2 as $row2) {
                            $keys = array_keys($row2);

                            Db::getInstance()->execute(
                                'INSERT INTO `'._DB_PREFIX_.$subtable.'`
                                VALUES ('.(int)$insertedId.', '.(int)$row['id_quantity_discount_rule'].', '.(int)$row2[$keys[2]].')'
                            );
                        }
                    }
                } elseif ($table == 'quantity_discount_rule_message') {
                    // Messages
                    $result2 = Db::getInstance()->executeS(
                        'SELECT *
                        FROM `'._DB_PREFIX_.'quantity_discount_rule_message_lang`
                        WHERE `id_quantity_discount_rule_message` = '.(int)$removedField['id_quantity_discount_rule_message']
                    );

                    foreach ($result2 as $row2) {
                        Db::getInstance()->execute(
                            'INSERT INTO `'._DB_PREFIX_.'quantity_discount_rule_message_lang` (`id_quantity_discount_rule_message`, `id_lang`, `message`)
                            VALUES ('.(int)$insertedId.', '.(int)$row2['id_lang'].', \''.$row2['message'].'\')'
                        );
                    }
                }
            }
        }

        return true;
    }

    public function renderList()
    {
        $this->addRowAction('duplicate');

        return parent::renderList();
    }

    public function renderForm()
    {
        $this->toolbar_btn['save-and-stay'] = array(
            'href' => '#',
            'desc' => $this->l('Save and Stay')
        );

        if (!$current_object = $this->loadObject(true)) {
            return;
        }

        $ruleConditions = array();
        $ruleActions = array();
        $ruleMessages = array();

        if (Validate::isLoadedObject($current_object)) {
            if ($groupConditions = $current_object->getGroups(true)) {
                foreach ($groupConditions as $groupCondition) {
                    $conditions = $groupCondition->getConditions();
                    foreach ($conditions as $condition) {
                        $condition = new QuantityDiscountRuleCondition((int)$condition['id_quantity_discount_rule_condition']);
                        $ruleConditions[(int)$groupCondition->id_quantity_discount_rule_group][(int)$condition->id_quantity_discount_rule_condition] = $condition;
                    }
                }
            }

            $actions = $current_object->getActions();
            if ($actions && array_filter($actions)) {
                foreach ($actions as $action) {
                    $ruleActions[] = new QuantityDiscountRuleAction((int)$action['id_quantity_discount_rule_action']);
                }
            }

            $messages = $current_object->getMessages();
            if ($messages && array_filter($messages)) {
                foreach ($messages as $message) {
                    $ruleMessages[] = new QuantityDiscountRuleMessage((int)$message['id_quantity_discount_rule_message']);
                }
            }
        } else {
            if (Tools::getValue('condition_id_type')) {
                $ruleConditions = $this->getConditionsFromPost();
            }

            if (Tools::getValue('action_id_type')) {
                $ruleActions = $this->getActionsFromPost();
            }

            if (Tools::getValue('message_hook_name')) {
                $ruleMessages = $this->getMessagesFromPost();
            }
        }


        if (!array_filter($ruleConditions)) {
            $quantityDiscountRuleCondition = new QuantityDiscountRuleCondition();
            $ruleConditions[1][] = $quantityDiscountRuleCondition->getNewCondition();
        }

        if (!array_filter($ruleActions)) {
            $ruleActions[] = new QuantityDiscountRuleAction();
        }

        if (!array_filter($ruleMessages)) {
            $ruleMessages[] = new QuantityDiscountRuleMessage();
        }

        $condition_counters = array();
        foreach ($ruleConditions as $key => $value) {
            $condition_counters[(int)$key] = (int)max(array_keys($value))+1;
        }

        $action_counter = end($ruleActions)->id_quantity_discount_rule_action;
        $message_counter = end($ruleMessages)->id_quantity_discount_rule_message;

        $this->context->controller->addJS(_PS_JS_DIR_.'tiny_mce/tiny_mce.js');
        $this->context->controller->addJS(_PS_JS_DIR_.'tinymce.inc.js');
        $this->context->controller->addJS(_PS_JS_DIR_.'admin/tinymce.inc.js');
        $iso = $this->context->language->iso_code;

        $times_used = Db::getInstance()->getValue(
            "SELECT count(*)
            FROM "._DB_PREFIX_."orders o
            LEFT JOIN "._DB_PREFIX_."order_cart_rule od ON o.id_order = od.id_order
            LEFT JOIN "._DB_PREFIX_."quantity_discount_rule_order qdro ON od.id_cart_rule = qdro.id_cart_rule
            WHERE qdro.id_quantity_discount_rule = ".(int)$current_object->id."
            AND ".(int)Configuration::get('PS_OS_ERROR')." != o.current_state"
        );

        $this->context->smarty->assign(
            array(
                'show_toolbar'          => true,
                'toolbar_btn'           => $this->toolbar_btn,
                'toolbar_scroll'        => $this->toolbar_scroll,
                'defaultDateFrom'       => date('Y-m-d H:00:00'),
                'defaultDateTo'         => date('Y-m-d H:00:00', strtotime('+10 year')),
                'times_used'            => $times_used,
                'conditions'            => $ruleConditions,
                'condition_counters'    => $condition_counters,
                'actions'               => $ruleActions,
                'action_counter'        => $action_counter,
                'message_counter'       => $message_counter,
                'messages'              => $ruleMessages,
                'show_button'           => true,
                'defaultCurrency'       => Configuration::get('PS_CURRENCY_DEFAULT'),
                'display_language'      => Configuration::get('PS_LANG_DEFAULT'),
                'languages'             => Language::getLanguages(false),
                'currencies'            => Currency::getCurrencies(false, false, true),
                'currentIndex'          => self::$currentIndex,
                'tpl_dir'               => $this->getTemplatePath(),
                'module_path'           => $this->module->getPathUri(),
                'currentToken'          => $this->token,
                'currentObject'         => $current_object,
                'currentTab'            => $this,
                'ad'                    => basename(_PS_ADMIN_DIR_),
                'tinymce'               => true,
                'path_css'              => _THEME_CSS_DIR_,
                'iso'                   => (version_compare(_PS_VERSION_, '1.6', '<') ? (file_exists(_PS_ROOT_DIR_.'/js/tiny_mce/langs/'.$iso.'.js') ? $iso : 'en') : (file_exists(_PS_CORE_DIR_.'/js/tiny_mce/langs/'.$iso.'.js') ? $iso : 'en')),
                'ajax'                  => false
            )
        );

        $this->content .= $this->createTemplate('form.tpl')->fetch();

        $this->addJqueryPlugin(array('jscroll', 'typewatch'));

        return parent::renderForm();
    }

    public function ajaxProcess()
    {
        if (Tools::isSubmit('newConditionGroup') && Tools::getValue('condition_group_id')) {
            $quantityDiscountRuleCondition = new QuantityDiscountRuleCondition();
            $conditions = array();
            $conditions[(int)Tools::getValue('condition_group_id')][] = $quantityDiscountRuleCondition->getNewCondition((int)Tools::getValue('condition_group_id'));

            $this->context->smarty->assign(
                array(
                    'tpl_dir'           => $this->getTemplatePath(),
                    'conditions'        => $conditions,
                    'currencies'        => Currency::getCurrencies(false, true),
                    'defaultCurrency'   => Configuration::get('PS_CURRENCY_DEFAULT'),
                    'ajax'              => true
                )
            );

            die($this->createTemplate('conditions.tpl')->fetch());
        }

        if (Tools::isSubmit('newCondition') && !is_null(Tools::getValue('condition_group_id')) && !is_null(Tools::getValue('condition_id'))) {
            $quantityDiscountRuleCondition = new QuantityDiscountRuleCondition();
            $this->context->smarty->assign(
                array(
                    'defaultCurrency'   => Configuration::get('PS_CURRENCY_DEFAULT'),
                    'currencies'        => Currency::getCurrencies(false, true),
                    'condition'         => $quantityDiscountRuleCondition->getNewCondition((int)Tools::getValue('condition_group_id'), (int)Tools::getValue('condition_id')),
                    'ajax'              => true
                )
            );

            die($this->createTemplate('condition.tpl')->fetch());
        }

        if (Tools::isSubmit('newAction') && Tools::getValue('action_id')) {
            $quantityDiscountRuleAction = new QuantityDiscountRuleAction();
            $quantityDiscountRuleAction->id_quantity_discount_rule_action = (int)Tools::getValue('action_id');
            $this->context->smarty->assign(
                array(
                    'defaultCurrency'   => Configuration::get('PS_CURRENCY_DEFAULT'),
                    'currencies'        => Currency::getCurrencies(false, true),
                    'action'            => $quantityDiscountRuleAction,
                    'type'              => Tools::getValue('type'),
                    'ajax'              => true
                )
            );
            die($this->createTemplate('action.tpl')->fetch());
        }

        if (Tools::isSubmit('newMessage') && Tools::getValue('message_id')) {
            $quantityDiscountRuleMessage = new quantityDiscountRuleMessage();
            $quantityDiscountRuleMessage->id_quantity_discount_rule_message = (int)Tools::getValue('message_id');
            $this->context->smarty->assign(
                array(
                    'display_language'  => Tools::getValue('lang'),
                    'languages'         => Language::getLanguages(false),
                    'message'           => $quantityDiscountRuleMessage,
                    'tinymce'           => false,
                    'ajax'              => true
                )
            );

            die($this->createTemplate('message.tpl')->fetch());
        }

        if (Tools::isSubmit('customerFilter')) {
            $search_query = trim(Tools::getValue('q'));
            $customers = Db::getInstance()->executeS('
            SELECT `id_customer`, `email`, CONCAT(`firstname`, \' \', `lastname`) as cname
            FROM `'._DB_PREFIX_.'customer`
            WHERE `deleted` = 0 AND is_guest = 0 AND active = 1
            AND (
                `id_customer` = '.(int)$search_query.'
                OR `email` LIKE "%'.pSQL($search_query).'%"
                OR `firstname` LIKE "%'.pSQL($search_query).'%"
                OR `lastname` LIKE "%'.pSQL($search_query).'%"
            )
            ORDER BY `firstname`, `lastname` ASC
            LIMIT 50');
            die(Tools::jsonEncode($customers));
        }
    }

    public function getConditionsFromPost()
    {
        $form_values = array();
        $definition = ObjectModel::getDefinition('QuantityDiscountRuleCondition');
        foreach (array_keys($definition['fields']) as $condition_var) {
            $form_values[$condition_var] = Tools::getValue('condition_'.$condition_var);
        }

        foreach ($this->condition_selectors as $condition_var) {
            $form_values['condition_'.$condition_var.'_select'] = Tools::getValue('condition_'.$condition_var.'_select_json');
        }

        $quantityConditionsRuleActionArray = array();

        //Foreach condition group
        if ($form_values['id_type'] && array_filter($form_values['id_type'])) {
            foreach (array_keys($form_values['id_type']) as $key => $quantity_discount_rule_group) {
                if ($form_values['id_type'][$quantity_discount_rule_group] && array_filter($form_values['id_type'][$quantity_discount_rule_group])) {
                    foreach (array_keys($form_values['id_type'][$quantity_discount_rule_group]) as $key2 => $quantity_discount_rule_group_rule) {
                        $quantityDiscountRuleCondition = new QuantityDiscountRuleCondition();

                        foreach (array_keys($definition['fields']) as $field) {
                            if (array_key_exists($field, $form_values)) {
                                $quantityDiscountRuleCondition->$field = $form_values[$field][$quantity_discount_rule_group][$quantity_discount_rule_group_rule];
                            }
                        }

                        $quantityDiscountRuleCondition->id_quantity_discount_rule_condition = (int)$key2;
                        $quantityDiscountRuleCondition->id_quantity_discount_rule_group = (int)$key;

                        foreach ($this->condition_selectors as $condition_selector) {
                            if (array_key_exists('condition_'.$condition_selector.'_select', $form_values) && $form_values['condition_'.$condition_selector.'_select']) {
                                foreach ($form_values['condition_'.$condition_selector.'_select'][$quantity_discount_rule_group] as $selectedValues) {
                                    if (array_filter($selectedValues)) {
                                        $keysToRemove = array();
                                        foreach (Tools::jsonDecode(reset($selectedValues)) as $selectedValue) {
                                            $key3 = array_search($selectedValue, array_column($quantityDiscountRuleCondition->{$condition_selector}['unselected'], 'id_'.$condition_selector));

                                            if ($key3 !== false) {
                                                $quantityDiscountRuleCondition->{$condition_selector}['selected'][] = $quantityDiscountRuleCondition->{$condition_selector}['unselected'][$key3];
                                                $keysToRemove[] = $key3;
                                            }
                                        }

                                        foreach ($keysToRemove as $key4) {
                                            unset($quantityDiscountRuleCondition->{$condition_selector}['unselected'][$key4]);
                                        }
                                    }
                                }
                            }

                            $quantityDiscountRuleCondition->{$condition_selector}['selected'] = array_values($quantityDiscountRuleCondition->{$condition_selector}['selected']);
                            $quantityDiscountRuleCondition->{$condition_selector}['unselected'] = array_values($quantityDiscountRuleCondition->{$condition_selector}['unselected']);
                        }

                        $quantityConditionsRuleActionArray[$key][] = $quantityDiscountRuleCondition;
                    }
                } else {
                    $quantityConditionsRuleActionArray[$key][] = new QuantityDiscountRuleCondition();
                }
            }
        }

        return $quantityConditionsRuleActionArray;
    }

    public function getActionsFromPost()
    {
        $form_values = array();
        $definition = ObjectModel::getDefinition('quantityDiscountRuleAction');
        foreach (array_keys($definition['fields']) as $action_var) {
            $form_values[$action_var] = Tools::getValue('action_'.$action_var);
        }

        foreach ($this->action_selectors as $action_var) {
            $form_values['action_'.$action_var.'_select'] = Tools::getValue('action_'.$action_var.'_select_json');
        }

        $quantityDiscountRuleActionArray = array();

        //Populate actions
        if ($form_values['id_type'] && array_filter($form_values['id_type'])) {
            foreach (array_keys($form_values['id_type']) as $key => $quantity_discount_rule_action) {
                $quantityDiscountRuleAction = new QuantityDiscountRuleAction();

                foreach (array_keys($definition['fields']) as $field) {
                    if (array_key_exists($field, $form_values) && isset($form_values[$field][$quantity_discount_rule_action])) {
                        $quantityDiscountRuleAction->$field = $form_values[$field][$quantity_discount_rule_action];
                    }
                }

                $quantityDiscountRuleAction->id_quantity_discount_rule_action = (int)$key;

                foreach ($this->action_selectors as $action_selector) {
                    if (array_key_exists('action_'.$action_selector.'_select', $form_values) && isset($form_values['action_'.$action_selector.'_select'][$quantity_discount_rule_action])) {
                        foreach ($form_values['action_'.$action_selector.'_select'][$quantity_discount_rule_action] as $selectedValues) {
                            if ($selectedValues) {
                                $keysToRemove = array();
                                foreach (Tools::jsonDecode($selectedValues) as $selectedValue) {
                                    $key = array_search($selectedValue, array_column($quantityDiscountRuleAction->{$action_selector}['unselected'], 'id_'.$action_selector));
                                    if ($key !== false) {
                                        $quantityDiscountRuleAction->{$action_selector}['selected'][] = $quantityDiscountRuleAction->{$action_selector}['unselected'][$key];
                                        $keysToRemove[] = $key;
                                    }
                                }

                                foreach ($keysToRemove as $key) {
                                    unset($quantityDiscountRuleAction->{$action_selector}['unselected'][$key]);
                                }
                            }
                        }
                    }

                    $quantityDiscountRuleAction->{$action_selector}['selected'] = array_values($quantityDiscountRuleAction->{$action_selector}['selected']);
                    $quantityDiscountRuleAction->{$action_selector}['unselected'] = array_values($quantityDiscountRuleAction->{$action_selector}['unselected']);
                }

                $quantityDiscountRuleAction->getGiftProduct((int)$key);
                $quantityDiscountRuleActionArray[] = $quantityDiscountRuleAction;
            }
        } else {
            $quantityDiscountRuleActionArray[] = new QuantityDiscountRuleAction();
        }

        return $quantityDiscountRuleActionArray;
    }

    public function getMessagesFromPost()
    {
        $form_values = array();
        foreach ($this->message_vars as $message_vars) {
            $form_values[$message_vars] = Tools::getValue('message_'.$message_vars);
        }

        $quantityDiscountRuleMessageArray = array();

        if (($form_values['hook_name'] && array_filter($form_values['hook_name'])) || ($form_values['message'] && array_filter(array_map('array_filter', $form_values['message'])))) {
            foreach (array_keys($form_values['hook_name']) as $key => $quantity_discount_rule_message) {
                $quantityDiscountRuleMessage = new QuantityDiscountRuleMessage();

                $definition = ObjectModel::getDefinition('quantityDiscountRuleMessage');
                foreach (array_keys($definition['fields']) as $field) {
                    if (array_key_exists($field, $form_values)) {
                        $quantityDiscountRuleMessage->$field = $form_values[$field][$quantity_discount_rule_message];
                    }
                }

                $quantityDiscountRuleMessage->id_quantity_discount_rule_message = (int)$key;

                $quantityDiscountRuleMessageArray[] = $quantityDiscountRuleMessage;
            }
        } else {
            $quantityDiscountRuleMessageArray[] = new QuantityDiscountRuleMessage();
        }

        return $quantityDiscountRuleMessageArray;
    }

    public function ajaxProcessSearchProducts()
    {
        $quantityDiscountRuleAction = new QuantityDiscountRuleAction();
        $array = $quantityDiscountRuleAction->searchProducts(Tools::getValue('product_search'));
        die(trim(Tools::jsonEncode($array)));
    }

    /* Quantity discount rules list callbacks */
    public function getCartRuleLink($id)
    {
        if ((int)$id) {
            return '<a href="'.$this->context->link->getAdminLink('AdminCartRules').'&id_cart_rule='.(int)$id.'&updatecart_rule">'.(int)$id.'</a>';
        }
    }

    public function getCartLink($id)
    {
        if ((int)$id) {
            return '<a href="'.$this->context->link->getAdminLink('AdminCarts').'&id_cart='.(int)$id.'&viewcart">'.(int)$id.'</a>';
        }
    }

    public function getOrderLink($id)
    {
        if ((int)$id) {
            return '<a href="'.$this->context->link->getAdminLink('AdminOrders').'&id_order='.(int)$id.'&vieworder">'.(int)$id.'</a>';
        }
    }

    public function getCustomerLink($id)
    {
        if ((int)$id) {
            $customer = new Customer($id);

            return '<a href="'.$this->context->link->getAdminLink('AdminCustomers').'&id_customer='.(int)$id.'&viewcustomer">'.(int)$id.' - '.$customer->firstname.' '.$customer->lastname.'</a>';
        }
    }
}
