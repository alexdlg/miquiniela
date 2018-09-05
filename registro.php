<?php
session_start();
require_once ("PHPMailer/class.phpmailer.php");
include("PHPMailer/class.smtp.php");

if ($_SERVER['REQUEST_METHOD'] == "POST")
  {
	  include("include/init.php");
	  include("genera_clave.php");
	  $Nombre = $_POST['nombre'];
	  $Apodo= $_POST['apodo'];
	  $Para = $_POST['email'];
	  $ClaveOriginal = dameTokenSix();
	  $Correo= explode("@", $_POST['email']);
	  $CorreoPre= $Correo[0];
	  $CorreoPost= $Correo[1];
	  $Celular = $_POST['celular'];	  
      $Clave = md5($ClaveOriginal);
	  $Sexo = $_POST['dcSexo'];
	  $Invitacion = explode(";", $_POST['txtInvitacion']);
	  $mensaje="";
	  
	  if ($CorreoPre != "")
	  {
			define('dbHost','localhost');
			define('dbUser','gordo_user');
			define('dbPass','3l$0Yns2tZ$658kd');
			define('dbName','quiniela');

			$conexion = new mysqli(dbHost, dbUser, dbPass, dbName);

			if ($conexion->connect_error) {
			 die("La conexion falló: " . $conexion->connect_error);
			}
			  $validacion = "CALL UserValidation('$CorreoPre','$CorreoPost')";
			  $validacionExec = $conexion->query($validacion);
			  
			  
			  if($validacionExec->num_rows > 0)
			  {
				  $row = $validacionExec->fetch_array(MYSQLI_ASSOC);
				  //echo $row['ExisteUsuario'];
				  if($row['ExisteUsuario'] > 0)
				  {
					  $mensaje = "1";
				  }
				  else
				  {
					  $mensaje = "0";
					   $sql = "INSERT INTO usuarios (IDUsuario, Nombre, CoreoPRE, CoreoPOST, Celular, Status, Apodo, KLV,InvitadoPor,Sexo) VALUES
											('','$Nombre','$CorreoPre','$CorreoPost','$Celular','1','$Apodo','$Clave','-1','$Sexo');";
											
			  
					  $modificacion = mysql_query($sql);
					  $mensaje_sql = $sql . " - " . mysql_error();
					  $IDUsuario = mysql_insert_id();
					  
					  
					  $salida = 1; 
							  
					  $sql = "UPDATE usuarios SET Sexion = '$IDUsuario' WHERE IDUsuario = '$IDUsuario'";
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
						//$Mensaje_panel = "Bienvenido a LaQuinielaDelGordo.com!<br /><br />te llego este correo porque alguien te registro en el sistema, si no deseas participar, ignora este correo. <br /><br />Para hacer tu quiniela, entra a <a href='http://www.laquinieladelgordo.com' target='_blank'>www.laquinieladelgordo.com</a> y como contraseña usa: <br /><b>".$ClaveOriginal."</b><br /><br />Recuerda que este mensaje es automatico, por favor, no lo respondas.<br /><br />Gracias por registrarte y suerte!!<br /><br />Atte. El Gordo de La Quiniela del Gordo";
						$Mensaje_panel =
						"
						<html xmlns='http://www.w3.org/1999/xhtml' lang='es' xml:lang='es'>
						<head>
							<meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
						</head>
						<link type='text/css' rel='stylesheet' href='http://dev.laquinieladelgordo.com/css/estilos.css' />
						<body>
							
							<table cellpadding='0' cellspacing='0' border='1' width='600px'>
							<tr>
								<td>
								<table cellpadding='10' cellspacing='10' border='0' width='600px'>
								<tr>
									<td align='left'>
										<p class='WelcomeMessage'>¡Bienvenido a la Quiniela del Gordo!</p>
									</td>
									<td align='right'>
										<img src='http://laquinieladelgordo.com/images/logo.png' width='100'>
									</td>
								</tr>
								<tr>
									<td colspan='2'>
										<p class='WelcomeMessage'>
											¡Gracias por registrarte con nosotros y ser parte de nuestra comunidad! 
										</p>
										
										<p class='WelcomeMessage'>
										Para hacer tu quiniela, entra a <a href='http://www.laquinieladelgordo.com' target='_blank'>www.laquinieladelgordo.com</a> y como contraseña usa: 
										".$ClaveOriginal."
										</p>
										
										<p class='WelcomeMessage'>
										Recuerda que este mensaje es automatico, por favor, no lo respondas.
										</p>
										
										<p class='WelcomeMessage'>
										Suerte en tus pronósticos.
										</p>
									</td>
								</tr>
								
								<tr>
									<td colspan='2' align='right'>
										<p class='WelcomeMessageAtte'>
											Atte:
											Staff de la Quiniela del Gordo
										</p>
										
									</td>
								</tr>
								
								</table>
							</td>
							</tr>
							</table>

						</body>
						</html>




						";
				
						$mail->ContentType = "text/html";
						$mail->SetFrom('notificaciones@laquinieladelgordo.com','El Gordo');
						$mail->Subject = 'Bienvenido a LaQuinielaDelGordo.com';
						$mail->Body = $Mensaje_panel;
						$mail->AddAddress($Para);        
						$mail->AddBcc("contacto@laquinieladelgordo.com");        
						$mail->Send();	  
					  
					  
					  
					  if ($modificacion)
						{
						$mensaje2 = "Registro Exitoso!";
						$color_mensaje = "#00FF00";
						}
					  else
						{
						$mensaje2 = "Error No. " . mysql_errno() . ": " . mysql_error();
						$cuerpo = $mensaje2 . "<br />Pagina: " . $_SERVER["SCRIPT_NAME"]. "<br />Ocurrido el dia: " . date("d-m-Y") . "<br />A las " . date("H:i:s");
						$cabeceras  = 'MIME-Version: 1.0' . "\r\n";
						$cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
						// Cabeceras adicionales
						$cabeceras .= 'From: <webmaster@coinfo.mx>' . "\r\n";
						mail("mgarcia@coinfo.mx","Error en Quiniela del Gordo", $cuerpo, $cabeceras);
						$color_mensaje = "#FF0000";
						}
			
				  }
				 // echo "mensaje=" . $mensaje;
				  
			  }
			  
			  
			  
		}
      else
	    {
			$mensaje = "-1";
		}
	  
	  
	  	  
  }
  
?>

<link type="text/css" rel="stylesheet" href="css/estilos.css" />

<form  name="forma" action="registro.php" method="POST">
<input type="hidden" name="txtClave1" value="" />
<center>
<style>
  .listado
  {
	  font-family:Verdana, Geneva, sans-serif;
	  font-size:14px;
  }
</style>

<table border="0" cellpadding="5" cellspacing="5" width="100%" align="center" class="listado">
  <tr height="30">
    <td align="center" bgcolor="#000000">
      <font color="#FFFFFF"><b>Conviértete en miembro</b></font>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" bgcolor="#FFFFFF">
		<table border="0" cellpadding="5" cellspacing="0"  bordercolor="#000000">
	<?php
	  if ($mensaje == "1")
		{
			echo "
				  <tr>
					<td colspan='2' align='center'>
					  <table border='0' cellpadding='5' cellspacing='0' width='100%' bordercolor='#000000'>
						<tr>
							<td align='center'>
								<img src='images/userexistsymbol.png' width='40' />
							</td>
							<td>
								<p class='UserRegistrationExist'>Usuario ya registrado</p>
							</td>
						</tr>
						
					  </table>
					</td>
				  </tr>		  
				 ";
		}
		else 
		if($mensaje == "0")
		{
			echo "
				  <tr>
					<td colspan='2' align='center'>
					  <table border='0' cellpadding='5' cellspacing='0' width='100%' bordercolor='#000000'>
						<tr>
							<td align='center'>
								<img src='images/successsymbol.png' width='40' />
							</td>
							<td>
								<p class='UserRegistrationNew'>Usuario Registrado Exitosamente; Revisa tu email para más información</p>
							</td>
						</tr>
						
					  </table>
					</td>
				  </tr>		  
				 ";
		}
		else
		if($mensaje == "-1")
		{
			echo "
				  <tr>
					<td colspan='2' align='center'>
					  <table border='0' cellpadding='5' cellspacing='0' width='100%' bordercolor='#000000'>
						<tr>
							<td align='center'>
								<img src='images/failuresymbol.png' width='40' />
							</td>
							<td>
								<p class='UserRegistrationExist'>Algo esta mal en la información proporcionada</p>
							</td>
						</tr>
						
					  </table>
					</td>
				  </tr>		  
				 ";
		}
	?>
		  <tr>
			<td>
				<label for="nombre">Nombre*:</label>
			</td>
			<td>
				<input type="text" class="form-control1" id="nombre" placeholder="Ingresa tu nombre" name="nombre" required>
				<p id="errorNombre" class="errors"></p>
			</td>    
		  </tr>
		  <tr>
			<td>
				<label for="apodo">Apodo:</label>
			</td>
			<td>
				<input type="text" class="form-control1" id="apodo" placeholder="Como quieres que te llamen" name="apodo">
			
			</td>    
		  </tr>
		  <tr>
			<td>
				<label for="dcSexo">Sexo</label>
			</td>
			<td>
				<label class="containerRegistrate">Hombre
					<input type="radio" name="dcSexo" value="h">
					<span class="checkmark"></span>
				</label>
					&nbsp;
				<label class="containerRegistrate">Mujer
					<input type="radio" name="dcSexo" value="m" />
					<span class="checkmark"></span>
				</label>
			</td>    
		  </tr>
		  <tr>
			<td>
				<label for="email">Email*:</label>
			</td>
			<td>
				<input type="email" class="form-control1" id="email" placeholder="Ingresa tu email" autocomplete="off" name="email" required>
				<p id="errorEmail" class="errors"></p>
				<p id="errorFEmail" class="errors"></p>
			</td>    
		  </tr>
		  <tr>
			<td>
				<label for="cemail">Confirma tu Email*:</label>
			</td>
			<td>
				<input type="email" class="form-control1" id="cemail" placeholder="Confirma tu email" autocomplete="off" name="cemail" required>
				<p id="errorCEmail" class="errors"></p>
			</td>    
		  </tr>
		  <tr>
			<td>
				<label for="celular">Celular:</label>
			</td>
			<td>	
				<input type="text" class="form-control1" id="celular" placeholder="Ingresa tu celular" name="celular">
			</td>    
		  </tr>
		  <tr>
			<td colspan="2"><font size="2"><b><i>*Campos Obligatorios</i></b></font></td>
		  </tr>
		  <tr>
			
			<td colspan="2" align="center">
				<input type="button" class="button buttonRegistro" value="Registrarme" onclick="valida_registro();" />
				<p class="lead">Recuerda agregar el correo <a href="mailto:notificaciones@laquinieladelgordo.com">notificaciones@laquinieladelgordo.com</a> 
				a tus contactos de confianza<br /></p>
			</td>
		  </tr>
		</table>
		
    </td>
  </tr>
</table>
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
  
function valida_registro()
{
	var mensajeNombre = "";
	var mensajeEmail = "";
	var mensajeCEmail = "";
	var mensajeFEmail = "";
	
	if (document.forma.nombre.value == "")
	  mensajeNombre= "No has capturado tu Nombre";
	
	if (document.forma.email.value == "")
	  mensajeEmail = "No has capturado tu Email";
	
	if (document.forma.cemail.value == "")
		mensajeCEmail = "No has capturado la confirmacion de Email";
	else
	if(validateEmail(document.forma.email.value))
	{
		if (document.forma.email.value != document.forma.cemail.value )
		  mensajeCEmail = "No coincide la informacion de Email";
	}
	else
	{
		mensajeFEmail = "Formato de Email incorrecto";
	}
	
	if(mensajeNombre != "" || mensajeEmail != "" || mensajeCEmail != "" || mensajeFEmail != "")
	{
		document.getElementById("errorNombre").innerHTML = mensajeNombre;
		document.getElementById("errorEmail").innerHTML = mensajeEmail;
		document.getElementById("errorCEmail").innerHTML = mensajeCEmail;
		document.getElementById("errorFEmail").innerHTML = mensajeFEmail;
	}
	else
		  document.forma.submit();
	
	/*
	if (document.forma.nombre.value == "")
	  alert("No has capturado tu Nombre");
	else
	if (document.forma.email.value == "")
	  alert("No has capturado tu Email");
	else
	if(validateEmail(document.forma.email.value))
	{
		if (document.forma.email.value != document.forma.cemail.value )
		  alert("No coincide la informacion de Email");
		else
		  document.forma.submit();
	}
	else
	{
		alert("Formato de Email incorrecto");
	}*/
	
}

function validateEmail(email) {
  var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  return re.test(email);
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

