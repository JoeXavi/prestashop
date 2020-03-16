<?php
/*
* 2007-2014 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2014 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_'))
	exit;

class Generalmodule extends Module
{
	/**
	* initiate Facebookpixel  module
	*/
	public function __construct()
	{
		$this->name = 'generalmodule';
		$this->tab = 'front_office_features';
		$this->version = '0.1';
		$this->author = 'Development by IDSO Web - Javier Saldaña';
		$this->need_instance = 1;
		$this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
		$this->bootstrap = true;
		$this->displayName = $this->l('General extension by IDSO Web');
		$this->description = $this->l('This is a general extension for multipurpose for Prestashop');

		$this->confirmUninstall = $this->l('Are you sure you want to uninstall Facebook Pixel?');
		parent::__construct();

 		!Configuration::updateValue('generalmodule', 'OK');
	}

	/**
	* install module *
	* @return bool
	*/
	public function install()
	{
		
		// Checking relevant hooks are available
		if (!parent::install() ||
			!$this->registerHook('displayHeader') ||
			!Configuration::updateValue('lightning_bolt', false) ||
			!$this->registerHook('displayBackOfficeHeader') ||
			!$this->registerHook('actionAdminControllerSetMedia') 			
		)			
		return false;

		// Create relevant variable
		return true;

	}

	/**
	* uninstall module
	* @return bool
	*/
	public function uninstall()
	{
		if (!parent::uninstall() ||
			!Configuration::deleteByName('generalmodule') ||
			!Configuration::deleteByName('lightning_bolt')
		)
		return false;

		//drop transaction table

		return true;
	}

	/**
	* back office return configuration form
	* @return mixed
	*/
	public function displayForm()
	{
		// Get default Language
		$default_lang = (int)Configuration::get('PS_LANG_DEFAULT');

		$helper = new HelperForm();

		// Module, t    oken and currentIndex
		$helper->module = $this;
		$helper->name_controller = $this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;

		// Language
		$helper->default_form_language = $default_lang;
		$helper->allow_employee_form_lang = $default_lang;

		// Title and toolbar
		$helper->title = $this->displayName;
		$helper->show_toolbar = true;        // false -> remove toolbar
		$helper->toolbar_scroll = true;      // yes - > Toolbar is always visible on the top of the screen.
		$helper->submit_action = 'submit'.$this->name;
		$helper->toolbar_btn = array(
			'save' =>
				array(
					'desc' => $this->l('Guardar'),
					'href' => AdminController::$currentIndex.'&configure='.$this->name.'&save'.$this->name.
					'&token='.Tools::getAdminTokenLite('AdminModules'),
				),
			'back' => array(
				'href' => AdminController::$currentIndex.'&token='.Tools::getAdminTokenLite('AdminModules'),
				'desc' => $this->l('Back to list')
			)
		);

		// Init Fields form array
		$fields_form[0]['form'] = array(
			'legend' => array(
				'title' => $this->l('lightning_bolt General Settings'),
			),
				'input' => array(
				//Enable o Disable lightning_bolt
					
					array(
						'type' => 'switch',
						'label' => $this->l('Enable lightning_bolt'),
						'name' => 'lightning_bolt',
						'is_bool' => true,
						'required' => true,
						'values' => array(
							array(
							'id' => 'lightning_bolt_yes',
							'value' => 1,
							'label' => $this->l('Yes'),
							),
							array(
							'id' => 'lightning_bolt_no',
							'value' => 0,
							'label' => $this->l('No')
							)
						),
						'hint' => $this->l('Enable or disable lightning_bolt')
					),
					 array(
	                    'type' => 'text',
	                    'label' => $this->l('Email Template'),
	                    'id' => 'emailTemplate',
	                    'name' => 'emailTemplate',
	                    'size' => 255),
					array(
                        'type' => 'html',
                        'label' => 'Test Email',
                        'name' => 'emial_test',
                        'html_content' => '<a class="btn btn-default" target="_blank" onclick="testEmail()">Ejecutar</a>',
	                    ),
			),
			'submit' => array(
				'title' => $this->l('Save'),
				'class' => 'btn btn-default pull-right'
			)
		);
		// Load current value
		
		$helper->fields_value['lightning_bolt'] = Configuration::get('lightning_bolt');

		return $helper->generateForm($fields_form);

	}

	/**
	* back office module configuration page content
	*/
	public function getContent()
	{
		$output = '';
		if (Tools::isSubmit('submit'.$this->name))
		{
			$error = false;
			$lightning_bolt = Tools::getValue('lightning_bolt');

			if (!$error)
			{
				Configuration::updateValue('lightning_bolt', $lightning_bolt);
				$output .= $this->displayConfirmation($this->l('Settings updated'));
			}
		}
		if (method_exists($this->context->controller, 'addJquery')) {
            $this->context->controller->addJquery();
  }
		return $output.$this->displayForm();
	}

	/**
	* hook page header to add CSS and JS files
	*/
	public function hookDisplayHeader()
	{
		if (Configuration::get('lightning_bolt') != '')
		{
			$this->context->smarty->assign(
				array(
					'lightning_bolt' => Configuration::get('lightning_bolt'),
				)
			);
			return $this->display(__FILE__, 'hookDisplayHeader.tpl');
		}

	}
	// Se puede añadir diferentes hooks de acuerdo a como se requiera
	public function hookDisplayBackOfficeHeader($params)
    {
        if (Tools::getValue('generalmodule') == "") {
            return;
        }
    }

    public function hookActionAdminControllerSetMedia($params)
	{ 
	    // Create a link with the good path
	    $link = new Link;
	    $parameters = array();
	    $ajax_link = $link->getModuleLink('generalmodule','ajax', $parameters);
	    Media::addJsDef(array('link' =>  $ajax_link));
	    $this->context->controller->addJS($this->_path.'views/js/custom.js');
	}
}
