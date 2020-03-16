
<?php
class GeneralmoduleAjaxModuleFrontController extends ModuleFrontController
{
 
	public function initContent()
	{
		$this->ajax = true;
		parent::initContent();
		
	}
 
	public function displayAjax()
	{
		die(Tools::jsonEncode(array('result' => "test")));
	}
 
}