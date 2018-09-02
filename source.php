<?php
session_start();
include("web/include/init.php");
$token = explode("~" , $_GET['token']);

$IDUsuario = $token[0];
$CoreoPRE = $token[1];
$KLV = $token[2];
$Adicional = $token[3];

$url = $_GET['url'];
if ($Adicional != "")
  $adicional = "&token=" . $Adicional;

    $sql = "SELECT * FROM usuarios WHERE CoreoPRE = '$CoreoPRE' AND KLV= '$KLV' AND md5(IDUsuario) = '$IDUsuario'";	
	$consulta = mysql_query($sql);
	if (mysql_num_rows($consulta) == 1)	
	  {
		  $renglon = mysql_fetch_assoc($consulta);		  
		  
		  $_SESSION['quiniela_matona_id'] = $renglon['IDUsuario'];
		  $_SESSION['quiniela_matona_admin'] = $renglon['Admin'];
		  $_SESSION['quiniela_matona_nombre'] = $renglon['Nombre'];
		  $sql_sesion = "UPDATE usuarios SET Sexion = '".session_id()."' WHERE IDUsuario = '".$renglon['IDUsuario']."'";
		  $modificacion_sesion = mysql_query($sql_sesion);
		  echo "<script language='javascript'>
				  window.location = '".$url.".php?session_id=".session_id(). $adicional . "@" . $renglon['IDUsuario'] . "';
				</script>
				";
				
	  }
	else
	  {
	  $_SESSION['quiniela_matona_y'] = "error";
      echo "<script language='javascript'>window.location = 'index.php'</script>";	  
	  }
?>