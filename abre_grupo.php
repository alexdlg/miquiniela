<?php
session_start();
header("Content-Type: text/html; charset=iso-8859-1"); 
include("include/init.php");

$token = explode("~", $_GET['token']);
$IDGrupo = $token[0];
$IDUsuario = $_SESSION['quiniela_matona_id'];

$sql = "SELECT * FROM grupos_social_jugadores WHERE IDGrupo = '$IDGrupo' AND IDJugador = '$IDUsuario'";
$consulta = mysql_query($sql);
if (mysql_num_rows($consulta) > 0)
  {
	$_SESSION['quiniela_matona_idgrupo'] = $IDGrupo;
  }
  
  
?>  