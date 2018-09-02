<?php
session_start();
$Sexion = session_id();
include("include/init.php");

if ($_SESSION['quiniela_matona_id'] != "")
  {
	$sql_sesion = "UPDATE usuarios SET Sexion = IDUsuario WHERE IDUsuario = '".$_SESSION['quiniela_matona_id']."'";
	$modificacion_sesion = mysql_query($sql_sesion);
  }
  
session_destroy();

echo "<script language='javascript'>window.location = 'index.php'</script>";	  
?>

