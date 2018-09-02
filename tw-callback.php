<?php
session_start();

function getUserIP()
{
    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = $_SERVER['REMOTE_ADDR'];

    if(filter_var($client, FILTER_VALIDATE_IP))
    {
        $ip = $client;
    }
    elseif(filter_var($forward, FILTER_VALIDATE_IP))
    {
        $ip = $forward;
    }
    else
    {
        $ip = $remote;
    }

    return $ip;
}

require "twitteroauth/autoload.php";

use Abraham\TwitterOAuth\TwitterOAuth;

$config['consumer_key']      = 'i71F7AZVIrRgJ9ERUj0Me3hv3';
$config['consumer_secret']   = 'YRa63bG1RYJWWrqt7b7v4HkZwSFJtfTxPiVLKDeCMlW8L314e6';
$config['url_login']         = 'http://www.laquinieladelgordo.com';
$config['url_callback']      = 'http://www.laquinieladelgordo.com/tw-callback.php';

$oauth_verifier = filter_input(INPUT_GET, 'oauth_verifier');
 
if (empty($oauth_verifier) ||
    empty($_SESSION['oauth_token']) ||
    empty($_SESSION['oauth_token_secret'])
) {
    // something's missing, go and login again
    header('Location: ' . $config['url_login']);
}


// connect with application token
$connection = new TwitterOAuth(
    $config['consumer_key'],
    $config['consumer_secret'],
    $_SESSION['oauth_token'],
    $_SESSION['oauth_token_secret']
);
 
// request user token
$token = $connection->oauth(
    'oauth/access_token', [
        'oauth_verifier' => $oauth_verifier
    ]
);


$TokenTwitterID = $token['user_id'];
$TokenTwitterName = $token['screen_name'];

include("include/init.php");

$sql = "SELECT * FROM usuarios WHERE twus3r1d = '$TokenTwitterID'";
$consulta = mysql_query($sql);
if (mysql_num_rows($consulta) == "1")
  {
	$renglon = mysql_fetch_assoc($consulta);		  
	
	$_SESSION['quiniela_matona_id'] = $renglon['IDUsuario'];
	$_SESSION['quiniela_matona_admin'] = $renglon['Admin'];
	$_SESSION['quiniela_matona_nombre'] = $renglon['Nombre'];
	$_SESSION['quiniela_matona_apodo'] = $renglon['Apodo'];
	if (session_id() != "")
	{
	  $sql_sesion = "UPDATE usuarios SET Sexion = '".session_id()."' WHERE IDUsuario = '".$renglon['IDUsuario']."'";
	  $modificacion_sesion = mysql_query($sql_sesion);
	}
	
	$user_ip = getUserIP();
	$Hoy = date("Y-m-d");
	$Ya = date("H:i:s");
	$sql_log = "INSERT INTO access_log (IDUsuario, Fecha, Hora, IPOrigen) VALUES ('".$renglon['IDUsuario']."','$Hoy','$Ya','$user_ip')";
	$insercion_log = mysql_query($sql_log);
	
	
	echo "<script language='javascript'>window.location = 'inicio.php?session_id=".session_id()."'</script>";
	  
	  
	  
	  
  }
else
  {
	  ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="es" xml:lang="es">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>La Quiniela del Gordo</title>
<!-- Required meta tags -->
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">

<!-- Add jQuery library -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>
<script src="js/cquery.min.js?ver=201806072"></script>
<link type="text/css" rel="stylesheet" href="css/estilos.css" />

<script type="text/javascript" src="https://code.jquery.com/jquery-latest.min.js"></script>



      
<form  name="forma" action="registro-tw.php" method="POST">
<input type="hidden" name="txtClave1" value="" />
<input type="hidden" name="txtCamino" value="" />
<input type="text" name="TokenTwitterID" value="<?php echo $TokenTwitterID;?>" />
<input type="text" name="TokenTwitterName" value="<?php echo $TokenTwitterName;?>" />
<center>

<div class="row tabcont">
    <div class="col-md-4"></div>
    <div class="col-md-4" >
      <b>Pre-Registro de Participante</b>
      <br><br>
	  Se ha detectado correctamente tu cuenta de Twitter, lamentablemente no se ha podido ligar a una cuenta valida 
	  dentro de LaQuinielaDelGordo.com
	  <br><br>
	  ¿Que procede?
      <br>
    </div>
    <div class="col-md-4"></div>
</div>

<div class="row tabcont">
    <div class="col-md-2"></div>
    <div class="col-md-4">      
      <br><br>
	  <table border="1" cellpadding="0" cellspacing="0"  bordercolor="#000000" width="80%"><tr><td>
	  <table border="0" cellpadding="10" cellspacing="0"  bordercolor="#000000" width="100%">
        <tr>
          <td align="center">
            <font color="#FF0000" size="+2"><b>Opcion No. 1</b></font>
          </td>
        </tr>
        <tr>
          <td>
            Si ya estas registrado y ya has participado en alguna temporada, 
            ingresa tu correo electronico y tu clave de acceso
            para poder ligar tu cuenta 
          </td>
        </tr>
	    <tr>
    	  <td>
            <b>*Correo</b><br>
	        <input type="text" name="txtCorreoTW"  value="" style="width:100%" autocomplete="new-password"/>
          </td>    
	    </tr>
	    <tr>
    	  <td>
            <b>*Clave de Acceso</b><br>
            <input type="password" name="txtClaveTW"  value="" style="width:100%" autocomplete="new-password"/>
          </td>    
	    </tr>
        <tr>
    	  <td align="center">
            <input type="button" value="Ingresar" onclick="valida_registro('tw');" />
          </td>
	    </tr>
	  </table>
      </td></tr></table>
    </div>
    <div class="col-md-4">
      <br><br>
	  <table border="1" cellpadding="0" cellspacing="0"  bordercolor="#000000" width="80%"><tr><td>
	  <table border="0" cellpadding="10" cellspacing="0"  bordercolor="#000000" width="100%">
        <tr>
          <td align="center">
            <font color="#FF0000" size="+2"><b>Opcion No. 2</b></font>
          </td>
        </tr>
        <tr>
          <td>
              Si no te has registrado aun, llena los siguientes datos para realizar tu registro, 
              recuerda agregar el correo "notificaciones@laquinieladelgordo.com" 
              a tus contactos seguros,
              para que el correo te llegue directo a INBOX... 
          </td>
        </tr>
	    <tr>
    	  <td>
            <b>*Nombre</b><br>
            <input type="text" name="txtNombre"  value="" style="width:100%"/>
          </td>    
	    </tr>
	    <tr>
    	  <td>
            <b>Apodo</b><br>
            <input type="text" name="txtApodo"  value="" style="width:100%"/>
          </td>    
	    </tr>
	    <tr>
    	  <td>
            <b>Sexo</b><br>
            <input type="radio" name="dcSexo" value="h" />Hombre
            &nbsp;&nbsp;&nbsp;
            <input type="radio" name="dcSexo" value="m" />Mujer
          </td>    
	    </tr>
	    <tr>
    	  <td>
            <b>*Correo Electronico</b><br>
            <input type="text" name="txtUsuario"  value="" style="width:100%" sutocomplete="new-password"/>
          </td>    
	    </tr>
	    <tr>
    	  <td>
            <b>*Confirma tu Correo Electronico</b><br>
            <input type="text" name="txtUsuario2"  value="" style="width:100%" sutocomplete="new-password"/>
          </td>    
	    </tr>
	    <tr>
    	  <td>
            <b>Celular</b><br>
            <input type="text" name="txtCelular"  value="" style="width:100%"/>
          </td>    
	    </tr>
	    <tr>
	      <td><font size="2"><b><i>*Campos Obligatorios</i></b></font></td>
	    </tr>
	    <tr>
    	  <td align="center"><input type="button" value="Registrarme" onclick="valida_registro('nw');" /></td>
	    </tr>
	  </table>
      </td></tr></table>
 
    </div>
    <div class="col-md-2"></div>
</div>


<br /><br />
</center>
</form>      
<script language="javascript">
function valida_registro(camino)
{
	if (camino == "tw")
		if (document.forma.txtCorreoTW.value == "")
		  alert("No has capturado tu Correo Electronico");
		else
		if (document.forma.txtCorreoTW.value == "")
		  alert("No has capturado tu Correo Electronico");
		else
		{
			document.forma.txtCamino.value = "tw";
			document.forma.submit();
		}
		  
		
	else
	{
		if (document.forma.txtNombre.value == "")
		  alert("No has capturado tu Nombre");
		else
		if (document.forma.txtUsuario.value == "")
		  alert("No has capturado tu Correo Electronico");
		else
		if (document.forma.txtUsuario.value != document.forma.txtUsuario2.value )
		  alert("No coincide la informacion de Correo Electronico");
		else
		if (document.forma.txtInvitacion.value == "")
		  alert("No has capturado tu Codigo de Invitacion");
		else
		  {
		    document.forma.txtCamino.value = "nw";
			document.forma.submit();
		  }
	}		
}

</script>
      <?php	  
  }


?>
