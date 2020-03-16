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

class AdminQuantityDiscountRulesFamiliesController extends ModuleAdminController
{
    public function __construct()
    {
        $this->bootstrap = true;
        $this->table = 'quantity_discount_rule_family';
        $this->className = 'QuantityDiscountRuleFamily';
        $this->addRowAction('edit');
        $this->addRowAction('delete');
        $this->_orderWay = 'DESC';

        parent::__construct();

        $this->bulk_actions = array('delete' => array('text' => $this->l('Delete selected'),'icon' => 'icon-trash', 'confirm' => $this->l('Delete selected items?')));

        $this->fields_list = array(
            'id_quantity_discount_rule_family' => array('title' => $this->l('ID'), 'align' => 'center', 'class' => 'fixed-width-xs'),
            'name' => array('title' => $this->l('Name')),
            'description' => array('title' => $this->l('Description'), 'align' => 'center'),
            'priority' => array('title' => $this->l('Priority'), 'class' => 'fixed-width-sm'),
            'execute_other_families' => array('title' => $this->l('Execute other families'), 'active' => 'execute_other_families', 'type' => 'bool', 'orderby' => false, 'align' => 'center'),
            'active' => array('title' => $this->l('Active'), 'active' => 'status', 'type' => 'bool', 'orderby' => false, 'align' => 'center'),
        );
    }

    public function setMedia($isNewTheme = false)
    {
        parent::setMedia($isNewTheme);

        if (version_compare(_PS_VERSION_, '1.6', '>=')) {
            $this->addCSS(_MODULE_DIR_.'quantitydiscountpro/views/css/admin.css');
        } else {
            $this->addCSS(_MODULE_DIR_.'quantitydiscountpro/views/css/admin-15.css');
        }
    }

    public function initPageHeaderToolbar()
    {
        if (empty($this->display)) {
            $this->page_header_toolbar_btn['new_quantity_discount_rule_family'] = array(
                'href' => self::$currentIndex.'&addquantity_discount_rule_family&token='.$this->token,
                'desc' => $this->l('Add new family', null, null, false),
                'icon' => 'process-icon-new'
            );

            $this->page_header_toolbar_btn['edit_quantity_discount_rule_family'] = array(
                'href' => $this->context->link->getAdminLink('AdminQuantityDiscountRules'),
                'desc' => $this->l('Back to rules', null, null, false),
                'icon' => 'process-icon-back'
            );
        }

        parent::initPageHeaderToolbar();

        $this->context->smarty->clearAssign('help_link', '');
    }

    public function initToolbar()
    {
        parent::initToolbar();

        if (empty($this->display)) {
            $this->toolbar_btn['new'] = array(
                'href' => self::$currentIndex.'&addquantity_discount_rule_family&token='.$this->token,
                'desc' => $this->l('Add new family', null, null, false),
            );

            $this->toolbar_btn['back'] = array(
                'href' => $this->context->link->getAdminLink('AdminQuantityDiscountRules'),
                'desc' => $this->l('Back to rules', null, null, false),
            );
        }
    }

    public function initProcess()
    {
        parent::initProcess();

        if (Tools::isSubmit('execute_other_families'.$this->table)) {
            $object = $this->loadObject();

            if (!Validate::isLoadedObject($object)) {
                $this->errors[] = Tools::displayError('An error occurred while updating carrier information.');
            }

            $object->execute_other_families = !$object->execute_other_families;
            if (!$object->update()) {
                $this->errors[] = Tools::displayError('An error occurred while updating carrier information.');
            }
            Tools::redirectAdmin(self::$currentIndex.'&token='.$this->token);
        }
    }

    public function renderForm()
    {
        $this->toolbar_btn['save-and-stay'] = array(
            'href' => '#',
            'desc' => $this->l('Save and Stay')
        );

        if (!$this->loadObject(true)) {
            return;
        }

        $this->fields_form = array(
            'legend' => array(
                'title' => $this->l('Family'),
                'icon' => 'icon-edit'
            ),
            'input' => array(
                array(
                    'type' => version_compare(_PS_VERSION_, '1.6', '>=') ? 'switch' : 'radio',
                    'label' => $this->l('Enabled?'),
                    'name' => 'active',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        )
                    )
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Name'),
                    'name' => 'name',
                    'required' => true,
                ),
                array(
                    'type' => 'textarea',
                    'label' => $this->l('Description'),
                    'name' => 'description',
                ),
                array(
                    'col' => 1,
                    'type' => 'text',
                    'label' => $this->l('Priority'),
                    'name' => 'priority',
                ),
                array(
                    'type' => version_compare(_PS_VERSION_, '1.6', '>=') ? 'switch' : 'radio',
                    'label' => $this->l('Execute rules from other families?'),
                    'name' => 'execute_other_families',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'execute_other_families_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ),
                        array(
                            'id' => 'execute_other_families_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        )
                    )
                ),
            )

        );

        $this->fields_form['submit'] = array(
            'title' => $this->l('Save')
        );

        return parent::renderForm();
    }

    public function initContent()
    {
        if ($warnings = $this->module->getWarnings(false)) {
            $this->errors[] = Tools::displayError($warnings);
            return;
        }

        parent::initContent();

        if (version_compare(_PS_VERSION_, '1.6', '>=')) {
            $this->context->smarty->assign(array(
                'this_path'                 => $this->module->getPathUri(),
                'support_id'                => '9129'
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

    public function processDelete()
    {
        $object = $this->loadObject();

        if (count(QuantityDiscountRule::getQuantityDiscountRulesByFamily($object->id_quantity_discount_rule_family)) > 0) {
            $this->errors[] = Tools::displayError('You cannot remove this family because there are rules associated to it.');
        } else {
            return parent::processDelete();
        }
    }
}
