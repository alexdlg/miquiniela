

<link type="text/css" rel="stylesheet" href="css/estilos.css" />

<form name="forma" method="post" action="reset_psw.php" >

<h2>
¿Olvidaste tu contraseña?
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
			<td align="center">
				<p class="lead">Ingresa tu dirección de correo para restaurarla.</p> 
				<input type="email" name="txtUser" placeholder="Correo Electrónico" class="form-control1">
				
				
			</td>
		  </tr>
		  <tr>
          	<td>
					
				<p class="lead"> Es posible que tengas que verificar tu carpeta de spam o desbloquear <br />
					<b>notificaciones@laquinieladelgordo.com</p>	
            </td>
          </tr>
          <tr>
            <td id="masinfo" align="center">
            	<input type="submit" class="button buttonRegistro" value="ENVIAR" id="submit">
				
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
