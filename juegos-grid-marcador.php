<?php
session_start();
header("Content-Type: text/html; charset=iso-8859-1"); 
require("include/init.php");
require("fechas.php");

$token = explode("~", $_GET['token']);

$IDUsuario = $_SESSION['quiniela_matona_id'];

$Casilla = $token[0];
$IDGrid = $token[1];
$Hash = $token[2];

$TokenHash = md5($Casilla . "@" . $IDGrid);

if ($TokenHash != $Hash)
  {
    echo "No envies parametros que no son, por favor";
	exit();
  }
  

$sql = "UPDATE juegos_grid_detalle SET Status = '1', IDUsuario = '$IDUsuario' WHERE IDGrid = '$IDGrid' AND Posicion = '$Casilla' AND Status = '0'";
$activacion = mysql_query($sql);

if (mysql_affected_rows() == "1")
  echo "<b>Casilla Comprada Exitosamente</b>";
else
  echo "<b><font color='#FF0000'>ERROR</font><br>
  		   La Casilla Elegida ya fue comprada por alguien mas, por favor, vuelve a intentarlo.</b>";

?>