<?php 

class Elimina_UsuarioEliminacionModuleFrontController extends ModuleFrontController
{
    public function initHeader(){

    	/////Inicializacion de varibles para plantilla 

    	$this->context->smarty->assign('direcciones','');
      	$this->context->smarty->assign('compras','');
      	$this->context->smarty->assign('carritos','');
      	$this->context->smarty->assign('mensajes','');
      	$this->context->smarty->assign('emailsibscriptionEstado','');
      	$mensaje1 = Configuration::get('MENSAJE1');
      	if($mensaje1<>"")
        	$this->context->smarty->assign('mensaje1',$mensaje1);
        else
        	$this->context->smarty->assign('mensaje1','Lamentamos que quieras eliminar tus datos de tiendanorma.com, recuerda debes volver a registrarte para realizar una nueva compra.');

        $titulomodulo = Configuration::get('TITULO');
      	if($titulomodulo<>"")
        	$this->context->smarty->assign('titulomodulo',$titulomodulo);
        else
        	$this->context->smarty->assign('titulomodulo','Eliminacion de datos');
       ///// FIN Inicializacion de varibles para plantilla 


        ///// Ahora verifico si se dio click en confirmacion de formulario
    	if (Tools::getIsset('submit'))
      	{
      	    
      		 ///// Optengo los datos de la desicion del usuario y el id de usuario
      	    $id_usuario = Tools::getValue('id_usuario');
      	    $desicion = Tools::getValue('SelectEliminacion');
      	    //var_dump($desicion);
      	    
      	    ///// Si decidio si empieso a eliminar datos asociados a el usuario
      	    if($desicion=="si"){
      	    	
      	    	///// Elimino direcciones
	      	    $sql = "select * from "._DB_PREFIX_."address where id_customer='".$id_usuario."'";
	      	    $res = Db::getInstance()->ExecuteS($sql);
	      	    	//var_dump($res);
	      	    	if(count($res)>0)
	      	    		$this->context->smarty->assign('direcciones',count($res).', Direcciones');
	      	    	else 
	      	    		$this->context->smarty->assign('direcciones','No existian direcciones');
	      	    
	      	    ///// Elimino pedidos
	      	    $sql = "select * from "._DB_PREFIX_."orders where id_customer='".$id_usuario."'";
	      	    $res = Db::getInstance()->ExecuteS($sql);
	      	    	//var_dump($res);
	      	    	if(count($res)>0)
	      	    		$this->context->smarty->assign('compras',count($res).', Compras');
	      	    	else 
	      	    		$this->context->smarty->assign('compras','No existian compras');
	      	    
	      	    ///// Elimino carritos de compra
	      	    $sql = "select * from "._DB_PREFIX_."cart where id_customer='".$id_usuario."'";
	      	    $res = Db::getInstance()->ExecuteS($sql);
	      	    	//var_dump($res);
	      	    	if(count($res)>0)
	      	    		$this->context->smarty->assign('carritos',count($res).', Carritos');
	      	    	else 
	      	    		$this->context->smarty->assign('carritos','No existian carritos');
	      	    
	      	     ///// Elimino mensajes
	      	    $sql = "select * from "._DB_PREFIX_."message where id_customer='".$id_usuario."'";
	      	    $res = Db::getInstance()->ExecuteS($sql);
	      	    	//var_dump($res);
	      	    	if(count($res)>0)
	      	    		$this->context->smarty->assign('mensajes',count($res).', Mensajes');
	      	    	else 
	      	    		$this->context->smarty->assign('mensajes','No existian mensajes asociados');

	      	     ///// Elimino suscripciones a email
	      	    $sql = "select * from "._DB_PREFIX_."emailsubscription where email='".Tools::getValue('correo')."'";
	      	    $res = Db::getInstance()->ExecuteS($sql);
	      	    	//var_dump($res);
	      	    	if(count($res)>0)
	      	    		$this->context->smarty->assign('emailsibscriptionEstado',count($res).', Suscripcion a novedades por correo electronico');
	      	    	else 
	      	    		$this->context->smarty->assign('emailsibscriptionEstado','No estaba suscrito a novedades por correo electronico');

	      	    ///// Elimino datos de conexiones realizadas
	      	    $sql = "select * from "._DB_PREFIX_."connections as con inner join "._DB_PREFIX_."guest as gue on gue.id_guest=con.id_guest where gue.id_customer='".$id_usuario."'";
	      	    $res = Db::getInstance()->ExecuteS($sql);
	      	    	//var_dump($res);
	      	    	if(count($res)>0)
	      	    		$this->context->smarty->assign('conecciones',count($res).', Registros de conexion');
	      	    	else 
	      	    		$this->context->smarty->assign('conecciones','No habia registros de conexion');

	      	   	      	    	//var_dump($res);
	      	    	
	      	}

	      	/* Si se decide que no eliminar datos, tomo el mensaje de la configuracion del modulo o de lo contrario se envia mensaje predeterminado*/
	      	else{
	      		 $mensaje2 = Configuration::get('MENSAJE2');
			      	if($mensaje2<>"")
			        	$this->context->smarty->assign('mensaje2',$mensaje2);
			        else
			        	$this->context->smarty->assign('mensaje2','Apreciamos tu decisión, te invitamos comprar todos los artículos que gustes en nuestra tienda :)');
	      	}

      	    			
      		$this->context->smarty->assign('save',true); 
	      		
      	}

      	else {
      		$this->context->smarty->assign('save',false); 
      	}
    }

    public function initContent()
    {
        parent::initContent();
        $cookie = $this->context->cookie->__isset('VerificacionEliminacionActualizacion');
        //var_dump($cookie);
        
        if (Tools::getIsset('submit') and Tools::getValue('SelectEliminacion')=="si" and $cookie==false)
        	{

        	$id_usuario = Tools::getValue('id_usuario');

        	$sql = "select * from "._DB_PREFIX_."guest where id_customer='".$id_usuario."'";
	      	    $res = Db::getInstance()->ExecuteS($sql);
	      	    	foreach ($res as $guest) {
	      	    		 $res = Db::getInstance()->delete('connections',"id_guest='".$guest['id_guest']."'");
	      	    	}

		    $res = Db::getInstance()->delete('emailsubscription',"email='".Tools::getValue('correo')."'");
		    $res = Db::getInstance()->delete('message',"id_customer='".$id_usuario."'");
		    $res = Db::getInstance()->delete('cart',"id_customer='".$id_usuario."'");
		    $res = Db::getInstance()->delete('orders',"id_customer='".$id_usuario."'");
		    $res = Db::getInstance()->delete('address',"id_customer='".$id_usuario."'");
		    
		    if(Db::getInstance()->delete('customer',"id_customer='".$id_usuario."'"))
	    		$this->context->smarty->assign('ResultadoEliminacion',true);
	    	else 
	    		$this->context->smarty->assign('ResultadoEliminacion',false);

	    	$this->context->cookie->__set('VerificacionEliminacionActualizacion', '1');
			$this->context->cookie->write();
	    	$this->setTemplate('module:elimina_usuario/views/templates/front/test.tpl');
        	}
        else
        {
	        
	        $this->context->cookie->__unset('VerificacionEliminacionActualizacion');

	        $sql = "SELECT id_customer,firstname FROM "._DB_PREFIX_."customer WHERE email= '".Tools::getValue('correo')."'";
	        $res = Db::getInstance()->ExecuteS($sql);
	        //var_dump($res);
	        if(count($res)>0)
	        {
		        $this->context->smarty->assign(
		    		array(
					    'usuario' => $res[0]['firstname'],
					    'id_usuario' => $res[0]['id_customer'],
					   ));

		        $this->setTemplate('module:elimina_usuario/views/templates/front/test.tpl');
	    	}
	    	else{
	    		$this->setTemplate('module:elimina_usuario/views/templates/front/test2.tpl');
	    	}
    	}
    }

}


?>