<link type="text/css" rel="stylesheet" href="css/estilos.css" />

<form name="forma_logueo" method="post" action="login.php" >
<input type="hidden" name="redes" id="redes" value="" />
<h2>
Login
</h2>

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tabla_login">
  <tr>
    <td>
    	<table width="100%" border="0" cellspacing="0" cellpadding="5">
          <tr>
          	<td>
            	<?php echo $mensaje_bd; ?>
            </td>
          </tr>
		  <tr>
			<td>
				<p class="lead">Conexion por redes sociales</p>
				  <img src="images/fb_login.png" width="200" onclick="facebook();" style="cursor:pointer" ></a>&emsp;
				  <img src="images/tw_login.png" width="200" onclick="twitter();" style="cursor:pointer" ></a>&emsp;
			</td>
		  </tr>
		  
		  <tr>
          	<td>
				<hr>
			</td>
		   </tr>
		  
          <tr>
				
          	<td>
				<p class="lead">Login por correo electronico</p>
                <label for="email">Usuario:</label>
                <input type="email" class="form-control" id="email" placeholder="Ingresa tu usuario" autocomplete="off" name="email" required>
            </td>
          </tr>
          <tr>
          	<td>
                <label for="pwd">Contraseña:</label>
                <input type="password" class="form-control" id="pwd" placeholder="Ingresa tu contraseña" autocomplete="off" name="pwd" required>
            </td>
          </tr>
          <tr>
            <td id="masinfo" align="center">
            	<input type="button" value="ENTRAR" onClick="logueo();">
				
            </td>
          </tr>
		  
		  <tr>
          	<td align="center">
    			<a style="cursor:pointer" href="forgot.php">&iquest;Olvidaste tu contrase&ntilde;a?</a>
            </td>
          </tr>
		  
          <tr>
          	<td>
            	
            </td>
          </tr>
        </table>
    </td>
  </tr>
</table>
</form>

<script language="javascript">

    var http = false;

	//verificacion del navegador para el uso de AJAX
    if(navigator.appName == "Microsoft Internet Explorer") {
      http = new ActiveXObject("Microsoft.XMLHTTP");
    } else {
      http = new XMLHttpRequest();
    }

function logueo()
{
	document.forma_logueo.submit();
}

function facebook()
{
	document.forma_logueo.redes.value = "fb";
	logueo();
}

function twitter()
{
	document.forma_logueo.redes.value = "tw";
	logueo();
}

</script>