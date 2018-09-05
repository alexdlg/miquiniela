<?php
include ("include/init.php");

$token = explode("@", $_GET['token']);
$Columna = $token[0];
$Valor = $token[1];
$IDUsuario = $token[2];

if ($Valor == "true")
  $Activacion = "1";
else
  $Activacion = "0";

switch ($Columna)
{
	case "1": $Campo = "AlarmaQuiniela";break;
	case "2": $Campo = "AlarmaPago";break;
	case "3": $Campo = "ReporteCierre";break;
	case "4": $Campo = "ReporteJuego";break;
	case "5": $Campo = "ReporteResultados";break;	
	case "6": $Campo = "Novedades";break;	
}

$sql = "UPDATE usuarios SET $Campo = '$Activacion' WHERE IDUsuario = '$IDUsuario'";
$ejecucion = mysql_query($sql);
?>