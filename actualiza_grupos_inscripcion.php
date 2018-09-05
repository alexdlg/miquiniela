<?php
session_start();
header("Content-Type: text/html; charset=iso-8859-1"); 
include("include/init.php");
require("genera_clave.php");
require("fechas.php");

$IDUsuarioSesion = $_SESSION['quiniela_matona_id'];
$IDLigaSesion  = $_SESSION['quiniela_matona_idliga'];

$token = explode("~", $_GET['token']);
$hash = $_GET['hash'];

$tokenHash = md5($_GET['token']);

if ($hash != $tokenHash)
  {  
    echo "Cambio en parametros, bye.";
	exit();
  }
$IDGrupo = $token[2];
$IDLiga = $token[5];
$IDUsuario = $token[7];

if ($IDUsuario != $IDUsuarioSesion)
  {  
    echo "Cambio en parametros, bye.";
	exit();
  }

if ($IDLiga != $IDLigaSesion)
  {  
    echo "Cambio en parametros, bye.";
	exit();
  }

$Hoy = date("Y-m-d");
$Ya = date("H:i:s");

$sql = "INSERT INTO grupos_social_jugadores (IDGrupo, IDJugador, FechaInscripcion, HoraInscripcion)
											VALUES
											('$IDGrupo','$IDUsuario','$Hoy','$Ya');";
$insercion = mysql_query($sql);											
?>