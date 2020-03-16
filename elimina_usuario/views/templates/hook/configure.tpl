{if $save}
	<div class="bootstrap">
		<div class="module_confirmation conf confirm alert alert-success">
			<button type="buttom" class="close" data-dismiss="alert">x</button>
			Url guardada correctamente
		</div>
	</div>
{/if}

<div class="bootstrap">
<form action="" method="post" id="module_form" class="defaultForm form-horizontal">
	<div class="form-group">
		<label for="titulomodulo" class="control-label" style="text-align: left;">Titulo de modulo</label>
		<input type="text" name="titulomodulo" class="form-control" id="titulomodulo" value="{$titulomodulo}" aria-describedby="titulomoduloHelp" placeholder="Eliminacion de datos de usuario">
		<small id="urlHelp" class="form-text text-muted">Ingrese titulo que aparecera desde front del modulo</small>
	</div>
	<div class="form-group">
		<label for="EjemploUrl" class="control-label" style="text-align: left;">Mensaje para confirmar eliminacion</label>
		<input type="text" name="EjemploUrl" class="form-control" id="EjemploUrl" value="{$urlvalue}" aria-describedby="urlHelp" placeholder="Introduzca texto para cuadro de confirmacion de eliminacion">
		<small id="urlHelp" class="form-text text-muted">Introduzca texto para cuadro de confirmacion de eliminacion</small>
	</div>
	<div class="form-group">
		<label for="mensajeno" class="control-label" style="text-align: left;">Mensaje si dice no eliminar</label>
		<input type="text" name="mensajeno" class="form-control" id="mensajeno" value="{$mensajeno}" aria-describedby="urlHelp" placeholder="Introduzca mensaje si dice no">
		<small id="urlHelp" class="form-text text-muted">Introduzca mensaje si dice no</small>
	</div> 
	<button type="submit" name="submitJjEjemplo" class="btn btn-primary">Enviar</button>
</form>
</div>