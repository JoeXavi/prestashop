<?php
/**
* 2007-2019 PrestaShop
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
*  @author    Javier Saldaña<joseph.xavi.sa@gmail.com>
*  @copyright 2019 IDSO web
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if(!defined('_PS_VERSION_'))
{
	exit;
}

class Elimina_Usuario extends Module
{
	public function __construct()
	{
        	$this->name = 'elimina_usuario';
                $this->tab = 'emailing';
                $this->version = '1.0.0';
                $this->author = 'IDSO web';
                $this->need_instance = 0;
                $this->boostrap = true;
                $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
                $this->displayName = 'Ley de protección de datos';
                $this->description = 'Para cumplir con las leyes de protección de datos se habilita este modulo para eliminar todos los datos de usuarios registrados en la tienda';
                parent::__construct();
	}

        public function install(){
                if(!parent::install() || !Configuration::updateValue('MENSAJE1','Lamentamos que quieras eliminar tus datos de la tienda, recuerda que cuando quieras volver a realizar una compra deberás volver a registrarte') || !Configuration::updateValue('TITULO','Eliminacion de datos') || !Configuration::updateValue('MENSAJE2','Apreciamos tu desicion, te invitamos comprar todos los articulos que gustes en nuestra tienda') || !$this->registerHook('actionEmailAddAfterContent'))
                        return false;
                return true
        ;
        }

        public function uninstall(){
                if(!parent::uninstall() || !Configuration::deleteByName('MENSAJE1') || !Configuration::deleteByName('TITULO') || !Configuration::deleteByName('MENSAJE2'))
                        return false;
                return true
        ;        
        }

    

        public function getContent(){
            $this->smarty->assign('action', 'index.php?controller=AdminModules&token='.Tools::getAdminTokenLite('AdminModules').'&configure='.$this->name);
            $this->smarty->assign('save',false);

            if(Tools::isSubmit('submitJjEjemplo')){
                        $myurl = Tools::getValue('EjemploUrl');
                        Configuration::updateValue('MENSAJE1', $myurl);
                        
                        $mensajeno = Tools::getValue('mensajeno');
                        Configuration::updateValue('MENSAJE2', $mensajeno);
                        
                        $titulomodulo = Tools::getValue('titulomodulo');
                        Configuration::updateValue('TITULO', $titulomodulo);
                        $this->smarty->assign('save',true);
            }
                $urlvalue = Configuration::get('MENSAJE1');
                $this->smarty->assign('urlvalue',$urlvalue);

                $mensajeno = Configuration::get('MENSAJE2');
                $this->smarty->assign('mensajeno',$mensajeno);

                $titulomodulo = Configuration::get('TITULO');
                $this->smarty->assign('titulomodulo',$titulomodulo);

                return $this->display(__FILE__,'configure.tpl');
        }
       

         public function hookActionEmailAddAfterContent(array $params){
            
            $html = explode('</body>', $params['template_html']) ;
            $html_resultante =  $html[0] . '<p style="color: gray; text-align: center; font-size: 12px;">Si no desea recibir mensajes puede dar click en el siguiente enlace para <a href="{shop_url}module/elimina_usuario/eliminacion?correo={email}" style="color: gray; font-size: 12px;">>eliminar suscripcion<</a></p></body>' . $html[1]  ;
            $params['template_html'] = $html_resultante;
            
            return $params;
        }
       
    }
