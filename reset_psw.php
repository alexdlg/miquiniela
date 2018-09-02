<?php 
session_start();
require("genera_clave.php");
require_once ("PHPMailer/class.phpmailer.php");
include("PHPMailer/class.smtp.php");
$mensaje = "";

if ($_SERVER['REQUEST_METHOD']== "POST")
{
	$Clave = dameTokenSix();	
	require("include/init.php");
	$Usuario = explode("@", $_POST['txtUser']);
	$Para = $_POST['txtUser'];
    $_SESSION['quiniela_matona_y'] = "reset";


	$sql = "SELECT * FROM usuarios WHERE CoreoPRE= '$Usuario[0]' AND CoreoPOST = '$Usuario[1]'";
	$consulta = mysql_query($sql);
	echo mysql_num_rows($consulta);
	if (mysql_num_rows($consulta) == 1)
	  {
		  echo "entre al if";
	$registro = mysql_fetch_assoc($consulta);
	
	$sql = "UPDATE usuarios SET Psw2 = '$Clave' WHERE CoreoPRE= '$Usuario[0]' AND CoreoPOST = '$Usuario[1]'";  //esta consulta es para la tabla MASTER
	$consulta = mysql_query($sql);	
	
	$format=explode(",",$_POST['display']);
	$mensaje="";
$mensaje = "
<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">
<html>
<head>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">
<title>Declarando.mx</title>
</head>
<body>";

$mensaje.="<br><br>";
//----------------------------------------------------------------------------------------------
  $mensaje.="
  <table border=\"0\" cellpadding=\"10\" cellspacing=\"0\" align=\"center\" width=\"600\" bgcolor='#FFFFFF'>   
    <tr align=\"left\">
      <td colspan=\"2\"><img src='http://www.laquinieladelgordo.com/images/logo.png'></td>
    </tr>
    <tr bgcolor=\"#EDEDED\">
      <td colspan=\"2\" align=\"center\">Reestablecimiento de Clave de La Quiniela del Gordo.com</td>
    </tr>
    <tr>
      <td colspan=\"2\" align=\"left\">
	  <font color='#000000' face='Verdana'>
	  <b>Estimado " . $registro['Nombre'] . ":</b><br /><p align='justify'>
	  Hemos reestablecido correctamente tu contraseña. Utiliza la siguiente clave de acceso: <br /><br />
	  <b>".$Clave."</b><br /><br />
	  Una vez dentro, podrás editar tu perfil, y establecer una contraseña de tu elección. 
	  <br /><br />
	  Atentamente.
	  <br /><br />
	  <b>Webmaster - LaQuinielaDelGordo.com</b>
	  </font>
	  </td>
    </tr>
  </table>  ";
//---------------------------------------------------------------------------------------------------------------------------------

	//Enviamos el mail
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
        $mail->Subject = 'Reset de Password de LaQuinielaDelGordo.com';
        $mail->Body = str_replace("\n", "<br />", $mensaje);
        $mail->AddAddress($Para);        
        $mail->Send();	  
  }  //esta llave cierra la verificacion de que si el usuario existe o no

}  //esta llave cierra la validacion de si es POST
?>

<script language="javascript">
  window.top.location = "index.php";
</script>


