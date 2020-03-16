<?php
/**
  * Export Products
  * @category export
  *
  * @author Oavea - Oavea.com
  * @copyright Oavea / PrestaShop
  * @license http://www.opensource.org/licenses/osl-3.0.php Open-source licence 3.0
  * @version 2.4.0
  */

class ExportProducts extends Module
{
	public function __construct()
	{
		$this->name = 'exportproducts';
		$this->tab = 'administration';
		$this->version = '2.4.0';
		$this->displayName = 'Export Products';
		$this->author = 'Oavea - oavea.com';
		$this->description = $this->l('A module to export all products to csv matching the Prestashop import template.');

		parent::__construct();
	}

	public function install()
	{
		$this->installController('AdminExportProducts', 'Export Products');
		return parent::install();

	}

	private function installController($controllerName, $name) {
		$tabId = (int) Tab::getIdFromClassName($controllerName);
        if (!$tabId) {
            $tabId = null;
        }

        $tab = new Tab($tabId);
        $tab->active = 1;
        $tab->class_name = $controllerName;
        $tab->name = array();
        foreach (Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']] = 'Export Products';
        }
        $tab->id_parent = (int) Tab::getIdFromClassName('AdminAdvancedParameters');
        $tab->module = $this->name;

        return $tab->save();
	}

	public function uninstall()
	{
		
		$this->uninstallController('AdminExportProducts');
		return parent::uninstall();
	}

	public function uninstallController($controllerName) {
		$tabId = (int) Tab::getIdFromClassName($controllerName);
        if (!$tabId) {
            return true;
        }

        $tab = new Tab($tabId);

        return $tab->delete();
	}

}
