{extends file=$layout}
{block name='content'}
    <div class="container">
    	<div class="row justify-content-center align-items-center">
    		<div class="col-xs-12 col-sm-6 "  style="background: #fff; padding: 10px; float: none; margin: 10px auto; border: 1px solid #ccc; box-shadow: 1px 1px 10px 2px #ccc; max-width: 400px">
    			{if $save}
    				{if !isset($mensaje2)}
    					{if $ResultadoEliminacion}
				    		<h1 style="text-align: center">Resultado de eliminacion</h1>
				    		<p style="text-align: justify-all;">Usuario, a continuacion los datos asociados a su cuenta que han sido eliminados:</p>
				    		{$direcciones}<br>
				    		{$compras}<br>
				    		{$carritos}<br>
				    		{$mensajes}<br>
				    		{$emailsibscriptionEstado}<br>
				    		{$conecciones}
				    	{else}
				    		<h1 style="text-align: center">Resultado de eliminacion</h1>
				    		<p style="text-align: justify-all;">Usuario, hemos tenido algunos problemas al eliminar sus datos por favor envianos un mensaje desde la seccion contactenos dando <a href="{$urls.pages.contact}">click aquí</a></p>
				    	{/if}
			    	{else}
			    		<h1 style="text-align: center">Resultado de eliminacion</h1>
		    			<p style="text-align: justify-all;">Señor(a) {$usuario}<br>{$mensaje2}</p>
		    		{/if}

	   			{else}
	   				<h1 style="text-align: center">{$titulomodulo}</h1>
		    		<p style="text-align: justify-all;">Señor(a) {$usuario}<br>{$mensaje1}</p>
		    		<form ction="{$link->getModuleLink('elimina_usuario', 'display')|escape:'html'}" method="post" name="formularioEliminacion">
		      			<div class="form-group">
		        			<label for="SelectEliminacion">Estas seguro de eliminar tus datos?</label>
		        			<select class="form-control" id="SelectEliminacion" name="SelectEliminacion">
		      					<option value="no">No</option>
		      					<option value="si">Si</option>
		      				</select>
		      			</div>
		      			<input type="hidden" value="{$id_usuario}" name="id_usuario">
		      			<button type="submit" class="btn btn-primary" name="submit" value="eliminacion">Eliminar</button>
		   			 </form>  
	   			 {/if}
	   		</div> 
    	</div>
    </div>
{/block}