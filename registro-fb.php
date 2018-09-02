<?php
session_start();
include ("encabezado_general.php");
require_once ("PHPMailer/class.phpmailer.php");
include("PHPMailer/class.smtp.php");
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

if ($_SERVER['REQUEST_METHOD'] == "POST")
  {
	  include("include/init.php");
	  include("genera_clave.php");
	  
	  
	  $Nombre = $_POST['txtNombre'];
	  $Apodo= $_POST['txtApodo'];
	  $Para = $_POST['txtUsuario'];
	  $ClaveOriginal = dameTokenSix();
	  $Correo= explode("@", $_POST['txtUsuario']);
	  $CorreoPre= $Correo[0];
	  $CorreoPost= $Correo[1];
	  $Celular = $_POST['txtCelular'];	  
      $Clave = md5($ClaveOriginal);
	  $Sexo = $_POST['dcSexo'];
	  $Invitacion = explode(";", $_POST['txtInvitacion']);
	  $Camino = $_POST['txtCamino'];
	  
	  if ($Camino == "")
	    exit();
	  else  
	    if ($Camino == "fb")
		  {
			$Usuario = explode("@", trim($_POST['txtCorreoFB']));
			$Clave = md5($_POST['txtClaveFB']);
			$Clave2 = $_POST['txtClaveFB'];
			
			$FBID  = $_POST['txtFBID'];
			$sql = "SELECT * FROM usuarios WHERE CoreoPRE = '$Usuario[0]' AND CoreoPOST = '$Usuario[1]' AND (KLV= '$Clave' OR Psw2 = '$Clave2')";			
			$consulta = mysql_query($sql);
			if (mysql_num_rows($consulta) == 1)	
			  {
				  $renglon = mysql_fetch_assoc($consulta);		  				
				  $_SESSION['quiniela_matona_id'] = $renglon['IDUsuario'];
				  $_SESSION['quiniela_matona_admin'] = $renglon['Admin'];
				  $_SESSION['quiniela_matona_nombre'] = $renglon['Nombre'];
				  $_SESSION['quiniela_matona_apodo'] = $renglon['Apodo'];
				  if (session_id() != "")
					{
					  $sql_sesion = "UPDATE usuarios SET Sexion = '".session_id()."', fbus3r1d = '".$FBID."' WHERE IDUsuario = '".$renglon['IDUsuario']."'";
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
				 
				  echo "<br><br><center><font color='#FF0000'><b>Error 61:</b></font> Tus datos de acceso son incorrectos, favor de verificarlo.";
				  exit();
			  }
		  }
		else
		{
	  
		  $sql = "SELECT * FROM usuarios WHERE TokenInvitacion = '".$Invitacion[0]."'";
		  $consulta = mysql_query($sql);
		  if (mysql_num_rows($consulta) == 1)
			{
				  $renglon = mysql_fetch_assoc($consulta);
				  $IDUsuario = $renglon['IDUsuario'];
				  $sql = "INSERT INTO usuarios (IDUsuario, Nombre, CoreoPRE, CoreoPOST, Celular, Status, Apodo, KLV,InvitadoPor,Sexo) VALUES
												('','$Nombre','$CorreoPre','$CorreoPost','$Celular','1','$Apodo','$Clave','$IDUsuario','$Sexo');";
												
				  
				  $modificacion = mysql_query($sql);
				  $mensaje_sql = $sql . " - " . mysql_error();
				  $IDUsuario = mysql_insert_id();
				  
				  
				  $salida = 1; 
				  do
					{
						$InvitacionNueva = rand(1000,9999);
						$sql_invitacion = "SELECT IDUsuario FROM usuarios WHERE TokenInvitacion = '".$InvitacionNueva."'";
						$consulta_invitacion = mysql_query($sql_invitacion);
						if ( mysql_num_rows($consulta_invitacion) > 0)
						  {
							$salida = 0;					  						
						  }
					} while ($salida == 0);
						  
				  $sql = "UPDATE usuarios SET Sexion = '$IDUsuario', TokenInvitacion = '$InvitacionNueva' WHERE IDUsuario = '$IDUsuario'";
				  $modificacion = mysql_query($sql);
				  
				  
				  
				  $mail = new PHPMailer();
			
			//CONFIGURACIONES DE SMTP
					$mail->IsSMTP(); // telling the class to use SMTP
					$mail->Username = "notificaciones@laquinieladelgordo.com";
					$mail->Password = "8ev7n3A*G_ry92t8";		
					$mail->Port     = 25;		
					$mail->SMTPSecure = "tls";
					$mail->Host       = "mail.laquinieladelgordo.com"; // SMTP server
					$mail->SMTPAuth   = true;                  // enable SMTP authentication
			//FIN DE CONFIGURACIONES
					$Mensaje_panel = "Bienvenido a LaQuinielaDelGordo.com!<br /><br />te llego este correo porque alguien te registro en el sistema, si no deseas participar, ignora este correo. <br /><br />Para hacer tu quiniela, entra a <a href='http://www.laquinieladelgordo.com' target='_blank'>www.laquinieladelgordo.com</a> y como contraseña usa: <br /><b>".$ClaveOriginal."</b><br /><br />Recuerda que este mensaje es automatico, por favor, no lo respondas.<br /><br />Gracias por registrarte y suerte!!<br /><br />Atte. El Gordo de La Quiniela del Gordo";
			
					$mail->ContentType = "text/html";
					$mail->SetFrom('notificaciones@laquinieladelgordo.com','El Gordo');
					$mail->Subject = 'Bienvenido a LaQuinielaDelGordo.com';
					$mail->Body = str_replace("\n", "<br />", $Mensaje_panel);
					$mail->AddAddress($Para);        
					$mail->AddBcc("el.chiks@gmail.com");        
					$mail->Send();	  
				  
				  
				  
			  if ($modificacion)
				{
				$mensaje = "Registro Exitoso!";
				$color_mensaje = "#00FF00";
				}
			  else
				{
				$mensaje = "Error No. " . mysql_errno() . ": " . mysql_error();
				$cuerpo = $mensaje . "<br />Pagina: " . $_SERVER["SCRIPT_NAME"]. "<br />Ocurrido el dia: " . date("d-m-Y") . "<br />A las " . date("H:i:s");
				$cabeceras  = 'MIME-Version: 1.0' . "\r\n";
				$cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
				// Cabeceras adicionales
				$cabeceras .= 'From: <webmaster@coinfo.mx>' . "\r\n";
				mail("mgarcia@coinfo.mx","Error en Quiniela del Gordo", $cuerpo, $cabeceras);
				$color_mensaje = "#FF0000";
				}
			}
		  else
			{
				  echo "<br><br><center><font color='#FF0000'><b>Error 143:</b></font> Clave de Invitacion no encontrada.";
				  exit();
			}
		}
	  
	  	  
  }
  
?>


<form  name="forma" action="registro-fb.php" method="POST">
<input type="hidden" name="txtClave1" value="" />
<input type="hidden" name="txtCamino" value="" />
<input type="hidden" name="txtFBID" value="<?php echo $_GET['fbid'];?>" />
<center>

<table border="0" cellpadding="0" cellspacing="0" width="1000" align="center" class="tabla_principal"><tr height="30"><td align="center"><br /><br />
<table border="1" cellpadding="0" cellspacing="0" width="100%" align="center" class="listado">
  <tr height="30">
    <td align="center" bgcolor="#009900" colspan="3">
      <font color="#FFFFFF"><b>Pre-Registro de Participante</b></font>
    </td>
  </tr>
  <tr>
    <td colspan='3' align='center'>
	  Se ha detectado correctamente tu cuenta de Facebook, lamentablemente no se ha podido ligar a una cuenta valida 
	  dentro de LaQuinielaDelGordo.com
	  <br><br>
	  ¿Que procede?	  
	</td>
  </tr>
  <tr>
    <td align="center" valign="top" width="47%" valign="top">
	  <font color="#FF0000"><b>Opcion No. 1:</b></font>
	  Si ya estas registrado y ya has participado esta temporada de Clausura 2017, ingresa tu correo electronico y tu clave de acceso
	  para poder ligar tu cuenta 
	  <table border="1" cellpadding="5" cellspacing="0"  bordercolor="#000000">
	    <tr>
    	  <td align="right" width="50%" bgcolor="#FFFFFF"><b>*Correo</b></td>
	      <td align="left" width="50%" bgcolor="#FFFFFF"><input type="text" name="txtCorreoFB"  value="" style="width:250px"/></td>    
	    </tr>
	    <tr>
	    <tr>
    	  <td align="right" width="50%" bgcolor="#FFFFFF"><b>*Clave de Acceso</b></td>
	      <td align="left" width="50%" bgcolor="#FFFFFF"><input type="password" name="txtClaveFB"  value="" style="width:250px"/></td>    
	    </tr>
	      <td>&nbsp;</td>
    	  <td><input type="button" value="Ingresar" onclick="valida_registro('fb');" /></td>
	    </tr>
	  </table>	  
	</td>
	<td width="6">&nbsp;</td>
    <td align="center" valign="top" width="47%" valign="top">
	  <font color="#FF0000"><b>Opcion No. 2:</b></font>
	  Si no te has registrado aun, llena los siguientes datos para realizar tu registro, <br>
      recuerda agregar el correo "notificaciones@laquinieladelgordo.com" <br>
      a tus contactos seguros,<br />
      para que el correo te llegue directo a INBOX... 
	  <table border="1" cellpadding="5" cellspacing="0"  bordercolor="#000000">
	    <tr>
    	  <td align="right" width="50%" bgcolor="#FFFFFF"><b>*Nombre</b></td>
	      <td align="left" width="50%" bgcolor="#FFFFFF"><input type="text" name="txtNombre"  value="" style="width:250px"/></td>    
	    </tr>
	    <tr>
    	  <td align="right" >Apodo</td>
	      <td align="left" ><input type="text" name="txtApodo"  value="" style="width:250px"/></td>    
	    </tr>
	    <tr>
    	  <td align="right" >Sexo</td>
	      <td align="left" >
            <input type="radio" name="dcSexo" value="h" />Hombre
            &nbsp;&nbsp;&nbsp;
            <input type="radio" name="dcSexo" value="m" />Mujer
          </td>    
	    </tr>
	    <tr>
    	  <td align="right" ><b>*Correo Electronico</b></td>
	      <td align="left" ><input type="text" name="txtUsuario"   value="" style="width:250px"/></td>    
	    </tr>
	    <tr>
    	  <td align="right" ><b>*Confirma tu Correo Electronico</b></td>
	      <td align="left" ><input type="text" name="txtUsuario2"   value="" style="width:250px" sutocomplete="off"/></td>    
	    </tr>
	    <tr>
	      <td align="right" >Celular</td>
    	  <td align="left" ><input type="text" name="txtCelular"   value=""style="width:250px"/></td>    
	    </tr>
	    <tr>
	      <td align="right" ><b>*Codigo de Invitacion</b></td>
    	  <td align="left" ><input type="text" name="txtInvitacion"   value=""style="width:250px"/></td>    
	    </tr>
	    <tr>
	      <td>&nbsp;</td>
	      <td><font size="2"><b><i>*Campos Obligatorios</i></b></font></td>
	    </tr>
	    <tr>
	      <td>&nbsp;</td>
    	  <td><input type="button" value="Registrarme" onclick="valida_registro('nw');" /></td>
	    </tr>
	  </table>
    </td>
  </tr>
</table>
<br /><br />
</td></tr></table>
<br /><br />
</center>
</form>

<script language="javascript">
    var http = false;
	
	//verificacion del navegador para el uso de AJAX
    if(navigator.appName == "Microsoft Internet Explorer") {
      http = new ActiveXObject("Microsoft.XMLHTTP");
    } else {
      http = new XMLHttpRequest();
    }

function getFile()
{
    document.getElementById("upfile").click();
}
  
function valida_registro(camino)
{
	if (camino == "fb")
		if (document.forma.txtCorreoFB.value == "")
		  alert("No has capturado tu Correo Electronico");
		else
		if (document.forma.txtCorreoFB.value == "")
		  alert("No has capturado tu Correo Electronico");
		else
		{
			document.forma.txtCamino.value = "fb";
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

function genera_clave()
{
      http.abort();
	  cadena="genera_clave_panel.php";
	  http.open("POST", cadena, true);
      http.onreadystatechange=function() 
	    {
        if(http.readyState == 4) 
		  {
 	      document.getElementById('txtClave1').value = http.responseText;
          }
        }
      http.send(null);	
}
</script>

